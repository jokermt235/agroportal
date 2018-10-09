<?php
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CharityController extends Controller
{

    public function indexAction(){
        return $this->render('@App/Charity/index.html.twig');
    }
}
