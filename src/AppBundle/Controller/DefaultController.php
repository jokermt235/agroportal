<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Advert;
use AppBundle\Entity\Section;
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'categories'=>$this->getCategories(),
            'sections'=> $this->getSections(),
            'adverts' => $this->getAdverts()
        ]);
    }

    private function getSections(){
        return $this->getDoctrine()
                ->getRepository(Section::class)
                ->findAll();
    }

    private function getCategories(){
        return $this->getDoctrine()
                ->getRepository(Category::class)
                ->findAll();
    }

    private function getAdverts(){
        return $this->getDoctrine()
                ->getRepository(Advert::class)
                ->findAll();
    }


}
