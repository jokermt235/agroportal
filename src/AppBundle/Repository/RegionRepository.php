<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Region;

class RegionRepository extends EntityRepository{   

    public function findAllByCountryId($country_id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Region u WHERE u.country_id=$country_id")
            ->getResult();
    }
}
