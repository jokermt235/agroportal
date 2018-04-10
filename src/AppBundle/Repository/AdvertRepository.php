<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AdvertRepository extends EntityRepository{   

    public function findAll(){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Advert u")
            ->getResult();
    }

    public function findAdvertByUserId($user_id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Advert u WHERE u.user_id='$user_id'")
            ->getResult();
    }

    public function getAdvertCountByUserId($user_id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT count(u) FROM AppBundle:Advert u WHERE u.user_id='$user_id'")
            ->getSingleScalarResult();
    }
    
    public function findAdvertById($id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Advert u WHERE u.id='$id'")
            ->getOneOrNullResult();
    }
}
