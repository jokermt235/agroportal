<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Publication;

class PublicationRepository extends EntityRepository{   

    public function findAll(){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Publication u ORDER BY u.created DESC")
            ->getResult();
    }

    public function findOneById($id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Publication u WHERE u.id = $id")
            ->getOneOrNullResult();
    }
}
