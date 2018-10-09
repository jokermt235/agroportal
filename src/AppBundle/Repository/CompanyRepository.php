<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Company;

class CompanyRepository extends EntityRepository{   

    public function findCompanyByUserId($user_id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Company u WHERE u.user_id='$user_id'")
            ->getResult();
    }

    public function findCompanyById($id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Company u WHERE u.id='$id'")
            ->getOneOrNullResult();
    }

    public function findAll(){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Company u ORDER BY u.id DESC")
            ->getResult();
    }

    public function findCompanyByUrl($url){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Company u WHERE u.url='$url'")
            ->getOneOrNullResult();
    }
}
