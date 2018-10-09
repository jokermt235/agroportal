<?php
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
class ReklamController extends Controller
{

    public function indexAction(){
        return $this->render('@App/Reklam/index.html.twig');
    }

}
