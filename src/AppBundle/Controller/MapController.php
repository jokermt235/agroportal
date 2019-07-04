<?php
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MapController extends Controller
{

    public function indexAction(Request $request)
    {
        return $this->render('@App/Map/index.html.twig');
    }

}

    
