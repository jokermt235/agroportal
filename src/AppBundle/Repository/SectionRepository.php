<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class SectionRepository extends EntityRepository{   

    public function findAll(){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Section u")
            ->getResult();
    }
}
