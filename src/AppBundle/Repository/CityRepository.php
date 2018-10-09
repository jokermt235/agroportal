<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\City;

class CityRepository extends EntityRepository{   

    public function findAllByRegionId($region_id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:City u WHERE u.region_id = $region_id")
            ->getResult();
    }
}
