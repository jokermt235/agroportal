<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Company;

class TopbannerRepository extends EntityRepository{   

    public function findAll(){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Topbanner u ORDER BY u.id DESC")
            ->getResult();
    } 
    
    public function findOneById($id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Topbanner u WHERE u.id=$id")
            ->setMaxResults(1)
            ->getResult()[0];
    }
}
