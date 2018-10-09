<?php
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Currency;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
class CurrencyController extends Controller
{

    public function indexAction(Request $request){
        return $this->render('@App/Currency/index.html.twig',['currencies'=>$this->getCurrency(),
        'selected'=>$request->get('name')]);
    }

    private function getCurrency(){
        return $this->getDoctrine()
                ->getRepository(Currency::class)
                ->findAll();
    }

}
