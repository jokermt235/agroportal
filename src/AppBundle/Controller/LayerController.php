<?php
namespace AppBundle\Controller;
use AppBundle\Entity\Layer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Finder\Finder;
use AppBundle\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;

class LayerController extends Controller
{

    public function addAction(Request $request, FileUploader $fileUploader)
    {
        if($request->isMethod('POST')){
            $path = $this->get('kernel')->getProjectDir().'/web/images/layer/';
            $em = $this->getDoctrine()->getManager();
            $layer = new Layer();
            $layer->setName($request->get('name'));

            $file_name = $fileUploader->upload($request->files->get('image'),$path);

            $layer->setIcon($file_name);
            
            $em->persist($layer);

            $em->flush();  
           
            return $this->forward('AppBundle:Layer:index');
        }
        return $this->render('@App/Admin/addlayer.html.twig');
    }

    public function indexAction(){
        return $this->render('@App/Admin/layer.html.twig',
            ['layers'=>$this->getDoctrine()
                        ->getRepository(Layer::class)
                        ->findAll()
            ]
        );
    }

    public function layersAction(){
        return $this->render('@App/Layer/index.html.twig',
            ['layers'=>$this->getDoctrine()
                        ->getRepository(Layer::class)
                        ->findAll(),
            'json_layers' => json_encode($this->getDoctrine()
                        ->getRepository(Layer::class)
                        ->findAllJson(), true)
            ]
        );
    }

    public function deleteAction($image,$id){
        if(!empty($image) && !empty($id)){
            $path = $this->get('kernel')->getProjectDir().'/web/images/layer/'.$image;
            $em = $this->getDoctrine()->getManager();
            $layer =$em->getRepository(Layer::class)->findOneById($id);
            $em->remove($layer);
            $em->flush();
            unlink($path);
        }
        return $this->forward('AppBundle:Layer:index');
    }

}
