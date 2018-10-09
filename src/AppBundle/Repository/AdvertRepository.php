<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AdvertRepository extends EntityRepository{   

    public function findAll(){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Advert u ORDER BY u.created DESC")
            ->getResult();
    }

    public function findAdvertByUserId($user_id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Advert u WHERE u.user_id='$user_id' ORDER BY u.created DESC")
            ->getResult();
    }

    public function getAdvertCountByUserId($user_id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT count(u) FROM AppBundle:Advert u WHERE u.user_id='$user_id' ORDER BY u.created DESC")
            ->getSingleScalarResult();
    }

    public function getAdvertViewCountByUserId($user_id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT SUM(u.views) as totalviews FROM AppBundle:Advert u WHERE u.user_id='$user_id' ORDER BY u.created DESC")
            ->getSingleScalarResult();
    }
    
    public function findAdvertById($id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Advert u WHERE u.id='$id'")
            ->getOneOrNullResult();
    }

    public function findAllByCategoryId($category_id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Advert u WHERE u.category_id='$category_id' ORDER BY u.created DESC")
            ->getResult();
    }

    public function findAllByCountryId($country_id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Advert u WHERE u.country_id='$country_id' ORDER BY u.created DESC")
            ->getResult();
    }

    public function findAllByCategoryIdAndCountryId($category_id, $country_id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Advert u WHERE u.category_id='$category_id' AND u.country_id='$country_id' ORDER BY u.created DESC")
            ->getResult();
    }

    public function findAllBySectionId($section_id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Advert u WHERE u.section_id='$section_id' ORDER BY u.created DESC")
            ->getResult();
    }

    public function findAllBySearchText($search_text){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Advert u WHERE u.title LIKE '%$search_text%' OR u.description LIKE '%$search_text%' ORDER BY u.created DESC")
            ->getResult();
    }

    public function findAllBySearchTextAndCategoryId($search_text, $search_category_id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Advert u WHERE u.title LIKE '%$search_text%' OR u.description LIKE '%$search_text%' AND u.category_id = '$search_category_id' ORDER BY u.created DESC")
            ->getResult();
    }
    
    public function findAdvertByCompanyId($company_id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Advert u WHERE u.company_id='$company_id' ORDER BY u.created DESC")
            ->getResult();
    }
}
