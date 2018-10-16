<?php
namespace AppBundle\Controller;
use AppBundle\Entity\Topbanner;
use AppBundle\Entity\Sidebanner;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Finder\Finder;
use AppBundle\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{

    public function indexAction(){
        return $this->render('@App/Admin/index.html.twig');
    }

    public function topbannerAction(Request $request, FileUploader $fileUploader){
        
        return $this->render('@App/Admin/topbanner.html.twig',
            ['topbanners'=>$this->getDoctrine()
                        ->getRepository(Topbanner::class)
                        ->findAll()
            ]
        );
    }

    public function addtopbannerAction(Request $request, FileUploader $fileUploader){
        
        if($request->isMethod('POST')){
            $path = $this->get('kernel')->getProjectDir().'/web/images/verh-new/';
            $em = $this->getDoctrine()->getManager();
            $topbanner = new Topbanner();
            $topbanner->setTitle($request->get('title'));
            $topbanner->setUrl($request->get('url'));

            $file_name = $fileUploader->upload($request->files->get('image'),$path);


            $topbanner->setImages($file_name);
            
            $em->persist($topbanner);

            $em->flush();  
           
            return $this->forward('AppBundle:Admin:topbanner');
        }
        
        return $this->render('@App/Admin/addtopbanner.html.twig');
    }


    public function topbannerdeleteAction($image,$id){
        $path = $this->get('kernel')->getProjectDir().'/web/images/verh-new/'.$image;
        $em = $this->getDoctrine()->getManager();
        $banner =$em->getRepository(Topbanner::class)->findOneById($id);
        $em->remove($banner);
        $em->flush();
        unlink($path);
        return $this->forward('AppBundle:Admin:topbanner');
    }

    public function addleftbannerAction(Request $request, FileUploader $fileUploader){
        if($request->isMethod('POST')){
            $path = $this->get('kernel')->getProjectDir().'/web/images/bok-new/';
            $em = $this->getDoctrine()->getManager();
            $sidebanner = new Sidebanner();
            $sidebanner->setTitle($request->get('title'));
            $sidebanner->setUrl($request->get('url'));

            $file_name = $fileUploader->upload($request->files->get('image'),$path);


            $sidebanner->setImages($file_name);
            
            $em->persist($sidebanner);

            $em->flush();  
           
            return $this->forward('AppBundle:Admin:leftbanner');
        }
        
        return $this->render('@App/Admin/addleftbanner.html.twig');
    }

    public function leftbannerAction(Request $request, FileUploader $fileUploader){
        

        /*if($request->isMethod('POST')){
            $path = $this->get('kernel')->getProjectDir().'/web/images/bok-new/';

            $fileUploader->upload($request->files->get('image'),$path);
        }

        $finder = new Finder();

        $files = [];
        
        $finder->files()->in($this->get('kernel')->getProjectDir().'/web/images/bok-new');
        foreach($finder as $file){
            $files[] = $file->getRelativePathname();
        }

        //$images = array_slice($files, $step, 5);
        
        return $this->render('@App/Admin/leftbanner.html.twig',
            ['images'=>$files]
        );*/

        return $this->render('@App/Admin/leftbanner.html.twig',
            ['sidebanners'=>$this->getDoctrine()
                        ->getRepository(Sidebanner::class)
                        ->findAll()
            ]
        );
    }

    public function sidebannerAction(Request $request, FileUploader $fileUploader){
        return $this->render('@App/Admin/sidebanner.html.twig',
            ['sidebanners'=>$this->getDoctrine()
                        ->getRepository(Sidebanner::class)
                        ->findAll()
            ]
        );
    }

    public function leftbannerdeleteAction($image,$id){ 

        $path = $this->get('kernel')->getProjectDir().'/web/images/bok-new/'.$image;
        $em = $this->getDoctrine()->getManager();
        $banner =$em->getRepository(Sidebanner::class)->findOneById($id);
        $em->remove($banner);
        $em->flush();
        unlink($path);
        return $this->forward('AppBundle:Admin:leftbanner');
    }

    public function layerAction(){
        return $this->render('@App/Admin/layer.html.twig'
            //['layers'=>$this->getDoctrine()
            //            ->getRepository(Layer::class)
            //            ->findAll()
            //]
        );
    }
}
