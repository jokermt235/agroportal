<?php
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CmpyController extends Controller
{

    public function aboutusAction(){
        return $this->render('@App/Cmpy/aboutus.html.twig');
    }

}
