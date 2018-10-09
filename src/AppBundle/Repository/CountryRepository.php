<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Country;

class CountryRepository extends EntityRepository{   

    public function findAll(){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Country u")
            ->getResult();
    }

    public function findOneById($id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Country u WHERE u.id='$id'")
            ->getOneOrNullResult();
    }

    public function findCountryById($id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Country u WHERE u.id='$id'")
            ->getOneOrNullResult();
    }
}
