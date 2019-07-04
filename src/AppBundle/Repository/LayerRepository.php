<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Layer;

class LayerRepository extends EntityRepository{   

    public function findAll(){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Layer u ORDER BY u.id DESC")
            ->getResult();
    } 

    public function findAllJson(){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Layer u ORDER BY u.id DESC")
            ->getArrayResult();
    }
    
    public function findOneById($id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:layer u WHERE u.id=$id")
            ->getOneOrNullResult();
    }
}
