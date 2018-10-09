<?php
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Region;
use Symfony\Component\HttpFoundation\Response;
class RegionController extends Controller
{

    public function indexAction($country_id=null){
        return $this->render('@App/Region/index.html.twig',['regions'=>$this->getRegionByCountryId($country_id)]);
    }

    private function getRegionByCountryId($country_id){
        return $this->getDoctrine()
                ->getRepository(Region::class)
                ->findAllByCountryId($country_id);
    }

}
