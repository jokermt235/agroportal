<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Category;

class CategoryRepository extends EntityRepository{   

    public function findAll(){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT c.id,c.name,count(a.id) cnt FROM AppBundle:Category c LEFT JOIN AppBundle:Advert a 
            WITH a.category_id = c.id GROUP BY c.id")
            ->getResult();
    }
}
