<?php
namespace AppBundle\Controller;
use AppBundle\Entity\User;
use AppBundle\Entity\Company;
use AppBundle\Entity\Country;
use AppBundle\Entity\Category;
use AppBundle\Entity\Advert;
use AppBundle\Entity\Marker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Finder\Finder;
use AppBundle\Service\FileUploader;
use AppBundle\Service\Translit;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Cookie;

class CompanyController extends Controller
{

    public function indexAction(){
        return $this->render('@App/Company/index.html.twig',[
            'categories'=>$this->getDoctrine()
                ->getRepository(Category::class)
                ->findAll(),
            'countries' => $this->getDoctrine()
                ->getRepository(Country::class)
                ->findAll(),
            'companies' => 
                $this->getDoctrine()
                ->getRepository(Company::class)
                ->findAll()
        ]);
    }

    public function usercompanyAction(Request $request){
        return $this->render('@App/Company/usercompany.html.twig',[
            'companies'=>$this->getDoctrine()
                        ->getRepository(Company::class)
                        ->findCompanyByUserId($this->getUser()->getId()),
            'selected'=>$request->get('id')
            ]
        );
    }

    public function viewAction($url=null, Request $request){
        /*$pattern = "/id-[0-9]+$/"; 
        preg_match($pattern,$url,$matches);
        if(!empty($matches)){
            $mat = explode("-",$matches[0]);
            $id = $mat[1];
        }*/

        $company = $this->getDoctrine()
            ->getRepository(Company::class)
            ->findCompanyByUrl($url);

        $response = new Response();
        if(empty($company)){
            if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                 # User is a ROLE_ADMIN
                return $this->render('@App/Admin/index.html.twig');
            }
        }

        if(empty($request->cookies->get('UserSession'))){
            $session = $this->get('session');
            $session->start();
            $session_id = $session->getId();
            $response->headers->setCookie(new Cookie('UserSession', $session_id));
            $company->setViews();
        }else{

        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($company);
        $em->flush();

        if(!empty($company)){

            return $this->render('@App/Company/view.html.twig',[
                'company'=> $company,
                'country' => $this->getDoctrine()
                            ->getRepository(Country::class)
                            ->findCountryById($company->getCountryId()),
                'adverts'=> $this->getDoctrine()
                            ->getRepository(Advert::class)
                            ->findAdvertByCompanyId($company->getId()),
                'marker' => $this->getDoctrine()->getRepository(Marker::class)
                            ->findOneByCompanyId($company->getId())
            ], $response);
        }

        return $this->redirect($this->generateUrl('homepage'));
    }

    public function editAction($url=null, Request $request, FileUploader $fileUploader, Translit $translit)
    {
        $company = $this->getDoctrine()
                ->getRepository(Company::class)
                ->findCompanyByUrl($url);

        if($request->isMethod('POST')){
            if(!empty($translit->createUrl($request->get('suburl')))){
                $url = $translit->createUrl($request->get('suburl'));
                $company = $this->getDoctrine()
                ->getRepository(Company::class)
                ->findCompanyByUrl($url);
                if(!empty($company) && $company->getId() !== $request->get('id')){
                    $session->getFlashBag()->add("error", "Уже есть ссылка с таким именем, придумайте другое название!");
                    return $this->render('@App/Company/edit.html.twig'); 
                }
            }
            $em = $this->getDoctrine()->getManager();
            $company = new Company();
            $company->setName($request->get('name'));
            $company->setUserId($this->getUser()->getId());
            $company->setCountryId($request->get('country_id'));
            $country->setContactName($request->get('contact_name'));

            $country = $this->getDoctrine()
                    ->getRepository(Country::class)
                    ->findOneById($this->request->get('country_id'));
            
            $phone = "";
            if($request->get("phone_code") && $request->get('phone_without_code')){
                $phone = $request->get("phone_code").$request->get('phone_without_code');
            }

            $targetDir = $this->get('kernel')->getProjectDir()."/web/uploads/images/company/";

            $file_name = $fileUploader->upload($request->files->get('image'),$targetDir);

            $company->setPhone($phone);
            $company->setAvatar($file_name);
            $company->setDescription($request->get('description'));
            $company->setPhoneCode($request->get('phone_code'));
            $company->setPhoneWithoutCode($request->get('phone_without_code'));
            
            $company->setUrl($translit->createUrl($request->get('suburl')));

            $em->persist($company);

            $em->flush();


            if(empty($request->get('suburl'))){
                $company->setUrl($translit->createUrl($request->get('suburl').' id '.$company->getId()));
            }
            
            $em->persist($company);

            $em->flush();
            
            $session->getFlashBag()->add("success", "This is a success message");
           
            return $this->forward('AppBundle:User:profile');
        }

        return $this->render('@App/Company/edit.html.twig',
            [
                'company' => $company,
                'country' => $country
            ]
        );
    }
        
    public function addAction(Request $request, FileUploader $fileUploader,Translit $translit){
        $session = $this->get('session');
        $session->start();
        if($request->isMethod('POST')){
            if(!empty($translit->createUrl($request->get('suburl')))){
                $url = $translit->createUrl($request->get('suburl'));
                $company = $this->getDoctrine()
                ->getRepository(Company::class)
                ->findCompanyByUrl($url);
                if(!empty($company)){
                    $session->getFlashBag()->add("error", "Уже есть ссылка с таким именем, придумайте другое название!");
                    return $this->render('@App/Company/add.html.twig'); 
                }
            }
            $em = $this->getDoctrine()->getManager();
            $company = new Company();
            $company->setName($request->get('name'));
            $company->setUserId($this->getUser()->getId());
            $company->setCountryId($request->get('country_id'));
            
            $phone = "";
            if($request->get("phonecode") && $request->get('phone_without_code')){
                $phone = $request->get("phonecode").$request->get('phone_without_code');
            }

            $targetDir = $this->get('kernel')->getProjectDir()."/web/uploads/images/company/";

            $file_name = $fileUploader->upload($request->files->get('image'),$targetDir);

            $company->setPhone($phone);
            $company->setPhoneCode($request->get('phonecode'));
            $company->setPhoneWithoutCode($request->get('phone_without_code'));
            $company->setAvatar($file_name);
            $company->setDescription($request->get('description'));
            $company->setContactName($request->get('contact_name'));
            $company->setLayerId($request->get('layer_id'));
            $company->setLat($request->get('lat'));
            $company->setLng($request->get('lng'));
            
            $company->setUrl($translit->createUrl($request->get('suburl')));

            $em->persist($company);

            $em->flush();


            if(empty($request->get('suburl'))){
                $company->setUrl($translit->createUrl($request->get('suburl').' id '.$company->getId()));
            }
            
            $em->persist($company);

            $em->flush();
            
            $session->getFlashBag()->add("success", "This is a success message");
           
            return $this->forward('AppBundle:User:profile');
        }
        return $this->render('@App/Company/add.html.twig',[
            'geoip'=> $this->getLocation($request)
        ]);
    }
    public function deleteAction($id = null, Request $request)
    {
        
        $company = $this->getDoctrine()
                ->getRepository(Company::class)
                ->findOneById($id);

        

        if($request->isMethod('POST'))
        {
            $em = $this->getDoctrine()->getManager(); 
            $em->remove($company);
            $em->flush();    
            $path = $this->get('kernel')->getProjectDir().
                '/web/uploads/images/company/'.$company->getAvatar();
            $fileSystem = new Filesystem();
            $fileSystem->remove($path);
        }

        return new Response('OK', Response::HTTP_OK);
    }

    private function createMarker($company_id, $layer_id, $status)
    {
        if(!$this->getDoctrine()
            ->getRepository(Marker::class)
            ->findOneByCompanyId($url))
        {
            $em = $this->getDoctrine()->getManager();
            $marker = new Marker();
            $marker->setCompanyId($company_id);
            $marker->setLayerId($layer_id);
            $marker->setStatus($status);
        }
    }

    private function getLocation($request){
        $record = $this->get('geoip2.reader')->city($request->getClientIp());
        return array('lat'=>$record->location->latitude,'lng'=>$record->location->longitude);
    }
}
