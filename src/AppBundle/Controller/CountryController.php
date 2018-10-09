<?php
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Country;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
class CountryController extends Controller
{

    public function indexAction(Request $request){
        return $this->render('@App/Country/index.html.twig',[
            'countries'=>$this->getCountry(),
            'selected'=> $request->get('id')
            ]
        );
    }

    private function getCountry(){
        return $this->getDoctrine()
                ->getRepository(Country::class)
                ->findAll();
    }

}
