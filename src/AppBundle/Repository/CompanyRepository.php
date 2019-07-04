<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Company;

class CompanyRepository extends EntityRepository{   

    public function findCompanyByUserId($user_id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u.id _id,u.name,u.avatar company_avatar,u.created,u.url,u.user_id,u.phone,m.id marker_id, m.vip,m.avatar marker_avatar,m.description, m.status marker_status FROM AppBundle:Company u LEFT JOIN AppBundle:Marker m WITH m.company_id = u.id WHERE u.user_id='$user_id'")
            ->getResult();

        //SELECT u.avatar,u.description,u.vip,u.status,c.url,l.icon,c.name company_name,c.phone,c.lat,c.lng  FROM AppBundle:Marker u INNER JOIN AppBundle:Company c WITH u.company_id = c.id INNER JOIN AppBundle:Layer l WITH u.layer_id = l.id"
    }

    public function findCompanyById($id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Company u WHERE u.id='$id'")
            ->getOneOrNullResult();
    }

    public function findOneById($id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Company u WHERE u.id='$id'")
            ->getOneOrNullResult();
    }

    public function findAll(){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Company u ORDER BY u.created DESC")
            ->getResult();
    }

    public function findCompanyByUrl($url){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Company u WHERE u.url='$url'")
            ->getOneOrNullResult();
    }

    public function findOneByIdAndUserId($company_id, $user_id)
    {
        return 
        $this->getEntityManager()
        ->createQuery("SELECT u FROM AppBundle:Company u WHERE u.id='$company_id' AND u.user_id=$user_id")
        ->getOneOrNullResult();
    }
}
