<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository{   

    public function findAll(){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Category u")
            ->getResult();
    }
}
