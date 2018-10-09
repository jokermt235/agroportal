<?php
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\City;
use Symfony\Component\HttpFoundation\Response;
class CityController extends Controller
{

    public function indexAction($region_id=null){
        return $this->render('@App/City/index.html.twig',['cities'=>$this->getCityByRegionId($region_id)]);
    }

    private function getCityByRegionId($region_id){
        return $this->getDoctrine()
                ->getRepository(City::class)
                ->findAllByRegionId($region_id);
    }

}
