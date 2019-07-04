<?php
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Company;
use AppBundle\Entity\Marker;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Service\FileUploader;
class MarkerController extends Controller
{

    public function addAction($company_id, Request $request)
    {   
        $token = md5(time());
        if(!empty($company_id)){
            if(empty($this->getDoctrine()
                ->getRepository(Company::class)
                ->findCompanyById($company_id))){
                return $this->render('@App/Marker/error.html.twig');
            }else{
                if(empty($this->getDoctrine()
                    ->getRepository(Company::class)
                    ->findOneByIdAndUserId($company_id,$this->getUser()->getId() ))){
                    return $this->render('@App/Marker/error_wrong_access.html.twig');
                }
            }
            $marker = $this->getDoctrine()
                ->getRepository(Marker::class)
                ->findOneByCompanyId($company_id);
            if(!empty($marker)){
                return $this->render('@App/Marker/error_already_exists.html.twig');
            }
        }

        if($request->isMethod('POST')){
            $em = $this->getDoctrine()->getManager();
            $marker = new Marker();
            $marker->setCompanyId($company_id);
            //$marker->setLayerId($request->get('layer_id'));
            //$marker->setLat($request->get('lat'));
            //$marker->setLng($request->get('lng'));
            $marker->setStatus($request->get('status'));
            $marker->setAvatar($request->get('avatar'));
            $marker->setDescription($request->get('description'));
            $em->persist($marker);
            $em->flush();
            
            /*$company = $this->getDoctrine()
                ->getRepository(Company::class)
                ->findCompanyById($company_id);
            $company->setHasMarker(1);
            
            $em->persist($marker);
            $em->flush();*/
            
            return $this->render('@App/User/profile.html.twig');
        }

        $record = $this->get('geoip2.reader')->city($request->getClientIp());

        return $this->render('@App/Marker/add.html.twig',
            [
                'geoip' => ['lat'=>$record->location->latitude,'lng'=>$record->location->longitude],
                'token' => $token
            ]
        );
    }

    public function editAction($company_id, Request $request)
    {
        $record = $this->get('geoip2.reader')->city($request->getClientIp());

        $marker = $this->getDoctrine()
                ->getRepository(Marker::class)
                ->findOneByCompanyId($company_id);
        if($request->isMethod('POST')){
            $em = $this->getDoctrine()->getManager();
            $marker->setLayerId($request->get('layer_id'));
            $marker->setLat($request->get('lat'));
            $marker->setLng($request->get('lng'));
            $marker->setStatus($request->get('status'));
            $em->persist($marker);
            $em->flush();
        }

        return $this->render('@App/Marker/edit.html.twig',
            [
                'geoip' => ['lat'=>$record->location->latitude,'lng'=>$record->location->longitude],
                'marker' => $marker
            ]
        );
    }

    public function checkAction(Request $request)
    {
        $response = new Response();
        $response->setContent(json_encode(array(
            'status' => true,
            )));
        $response->headers->set('Content-Type', 'application/json');
        if($request->isMethod('POST')){
            if(!empty($request->get('lat')) && !empty($request->get('lng'))){
                $marker = $this->getDoctrine()
                ->getRepository(Marker::class)
                ->findOneByLatLng($request->get('lat'), $request->get('lng'));
                if(!empty($marker)){
                    $response->setContent(json_encode(array(
                        'status' => true,
                    )));

                    return $response;
                }
            }
        }

        return $response;
    }

    public function deleteAction($id, Request $request)
    {   
        if($request->isMethod('POST')){
            $marker = 
                $this->getDoctrine()
                    ->getRepository(Marker::class)
                    ->findOneById($id);
            $em = $this->getDoctrine()->getManager(); 
            $em->remove($marker);
            $em->flush();
            
            $company = $this->getDoctrine()
                ->getRepository(Company::class)
                ->findOneById($marker->getCompanyId());

            $em->persist($company);
            $em->flush();
            
            $response = new Response();
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent('{status: "ok"}');
        }
        return $response;
    }

    public function indexAction()
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $markers = $this->getDoctrine()
                ->getRepository(Marker::class)
                ->findAllJson();
        $response->setContent($markers);
        return $response;
    }

    public function uploadAction(Request $request, FileUploader $fileUploader){
        $file = $request->files->get('files')[0];
        $token = $request->headers->get('Token');
        return new Response($fileUploader->getFilePreview($file,$token, 128));
    }

    public function updateAction(Request $request)
    {
        $id = $request->get("id");
        $marker = $this->getDoctrine()
                ->getRepository(Marker::class)
                ->findOneById($id);
        if($request->isMethod('POST')){
            $em = $this->getDoctrine()->getManager();
            $marker->setStatus($request->get('status'));
            $em->persist($marker);
            $em->flush();
        }
        return new Response("OK");
    }
}
