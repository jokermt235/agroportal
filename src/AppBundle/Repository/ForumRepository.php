<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Category;

class ForumRepository extends EntityRepository{   

    public function findAll(){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Forum u")
            ->getResult();
    }

    public function findAllByCategoryId($category_id){
        return 
            $this->getEntityManager($category_id)
            ->createQuery("SELECT u FROM AppBundle:Forum u WHERE u.category_id = $category_id")
            ->getResult();
    }

    public function findById($id){
        return 
            $this->getEntityManager($id)
            ->createQuery("SELECT u FROM AppBundle:Forum u WHERE u.id = $id")
            ->getOneOrNullResult();
    }
}
