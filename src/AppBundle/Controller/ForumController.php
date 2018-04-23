<?php
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Forum;
use AppBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
class ForumController extends Controller
{

    public function indexAction(){
        $response = new JsonResponse('<h1>Hello World</h1>');
        return $this->render('@App/Forum/index.html.twig');
    }

    public function addAction(Request $request){
        return $this->render('@App/Forum/add.html.twig',['categories' => $this->getCategories()]);
    }

    private function getCategories(){
        return $this->getDoctrine()
                ->getRepository(Category::class)
                ->findAll();
    }

}
