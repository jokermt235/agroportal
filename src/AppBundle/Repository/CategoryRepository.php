<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Category;

class CategoryRepository extends EntityRepository{   

    public function findAll(){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT c.id,c.name,count(a.id) cnt FROM AppBundle:Advert a JOIN AppBundle:Category c 
            WITH a.category_id = c.id GROUP BY a.category_id")
            ->getResult();
    }
}
