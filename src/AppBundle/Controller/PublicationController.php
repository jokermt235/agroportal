<?php
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PublicationController extends Controller
{

    public function indexAction(){
        return $this->render('@App/Publication/index.html.twig');
    }
}
