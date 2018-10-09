<?php
namespace AppBundle\Controller;
use AppBundle\Entity\Topbanner;
use AppBundle\Entity\Sidebanner;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Finder\Finder;
class CmpyController extends Controller
{

    public function aboutusAction(){
        return $this->render('@App/Cmpy/aboutus.html.twig');
    }

    public function rulesAction(){
        return $this->render('@App/Cmpy/rules.html.twig');
    }

    public function topbannerAction($step=0){
        $finder = new Finder();
        
        $finder->files()->in($this->get('kernel')->getProjectDir().'/web/images/verh-new');
        foreach($finder as $file){
            $files[] = $file->getRelativePathname();
        }

        $images = array_slice($files, $step, 10);
        
        return $this->render('@App/Cmpy/topbanner.html.twig',['images'=> $images,
            'topbanners'=>$this->getDoctrine()
                        ->getRepository(Topbanner::class)
                        ->findAll()
            ]
        );
    }

    public function leftbannerAction($step=0){

        $finder = new Finder();
        
        $finder->files()->in($this->get('kernel')->getProjectDir().'/web/images/bok-new');
        foreach($finder as $file){
            $files[] = $file->getRelativePathname();
        }

        $images = array_slice($files, $step, 10);
        
        return $this->render('@App/Cmpy/leftbanner.html.twig',['images'=> $images,
            'leftbanners'=>$this->getDoctrine()
                        ->getRepository(Sidebanner::class)
                        ->findAll()
            ]
        );
    }

    public function logoAction(){
        $finder = new Finder();
        
        $finder->files()->in($this->get('kernel')->getProjectDir().'/web/images/small_rotate_logo');
        foreach($finder as $file){
            $files[] = $file->getRelativePathname();
        }
        return $this->render('@App/Cmpy/logo.html.twig',['images'=> $files]);
    }

}
