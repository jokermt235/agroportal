<?php
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
class MapController extends Controller
{

    public function indexAction()
    {
        return $this->render('@App/Map/index.html.twig');
    }
}

    
