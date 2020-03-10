<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Advert;
use AppBundle\Entity\Section;
use AppBundle\Entity\Category;
use AppBundle\Entity\Country;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;

class AgroovController extends Controller
{
    
    public function indexAction(Request $request)
    {
        if(!empty($request->get('search_text'))){
            $adverts = $this->getAdvertsBySearchText($request->get('search_text'));
            if(!empty($request->get('search_category_id'))){
                $adverts = $this->getAdvertsBySearchTextAndCategoryId(
                    $request->get('search_text'),
                    $request->get('search_category_id')
                );
            }
        }else{
            if(empty($request->get('search_category_id'))){
                if(!empty($request->get('category_id'))){
                    if(!empty($request->get('country_id'))){
                        $adverts = $this->getAdvertsByCategoryIdAndCountryId(
                            $request->get('category_id'),$request->get('country_id')
                        );
                    }else{
                        $adverts = $this->getAdvertsByCategoryId($request->get('category_id'));
                    }
                }else{
                    if(!empty($request->get('country_id'))){
                        $adverts = $this->getAdvertsByCountryId($request->get('country_id'));
                    }else{
                        $adverts = $this->getAdverts();
                    }
                    if(!empty($request->get('section_id'))){
                        $adverts = $this->getAdvertsBySectionId($request->get('section_id'));
                    }else{
                        if(empty($request->get('country_id'))){
                            $adverts = $this->getAdverts();
                        }
                    }
                }
            }else{
                $adverts = $this->getAdvertsByCategoryId($request->get('search_category_id'));
            }
        }

        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'categories'=>$this->getCategories(),
            'sections'=> $this->getSections(),
            'adverts' => $adverts,
            'geoip'=> $this->getLocation($request),
            'countries' => $this->getDoctrine()
                ->getRepository(Country::class)
                ->findAll()
        ]);
    }
    
    private function getSections(){
        return $this->getDoctrine()
                ->getRepository(Section::class)
                ->findAll();
    }

    private function getCategories(){
        return $this->getDoctrine()
                ->getRepository(Category::class)
                ->findAll();
    }

    private function getAdverts(){
        return $this->getDoctrine()
                ->getRepository(Advert::class)
                ->findAll();
    }

    private function getAdvertsByCategoryId($category_id){
        return $this->getDoctrine()
                ->getRepository(Advert::class)
                ->findAllByCategoryId($category_id);
    }

    private function getAdvertsByCountryId($country_id){
        return $this->getDoctrine()
                ->getRepository(Advert::class)
                ->findAllByCountryId($country_id);
    }

    private function getAdvertsBySectionId($section_id){
        return $this->getDoctrine()
                ->getRepository(Advert::class)
                ->findAllBySectionId($section_id);
    }

    private function getAdvertsByCategoryIdAndCountryId($category_id, $country_id){
        return $this->getDoctrine()
                ->getRepository(Advert::class)
                ->findAllByCategoryIdAndCountryId($category_id, $country_id);
    }

    private function getAdvertsBySearchText($search_text){
        return $this->getDoctrine()
                ->getRepository(Advert::class)
                ->findAllBySearchText($search_text);
    }

    private function  getAdvertsBySearchTextAndCategoryId($search_text, $search_category_id){
            
        return $this->getDoctrine()
            ->getRepository(Advert::class)
            ->findAllBySearchTextAndCategoryId($search_text, $search_category_id);
    }

    private function getLocation($request){
        $record = $this->get('geoip2.reader')->city($request->getClientIp());
        //print_r($record->city->name);
        return array('lat'=>$record->location->latitude,'lng'=>$record->location->longitude);
    }
}
