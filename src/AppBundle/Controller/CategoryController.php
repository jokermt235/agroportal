<?php
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
class CategoryController extends Controller
{

    public function indexAction(){
        $categories = $this->getDoctrine()
                ->getRepository(Category::class)
                ->findAll();

        $response = new JsonResponse();
        $response->setData($categories);
        return $response;
    }

}
