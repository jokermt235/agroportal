<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Marker;

class MarkerRepository extends EntityRepository{   

    public function findAll(){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Marker u ORDER BY u.id DESC")
            ->getResult();
    } 

    public function findAllByLayerId($layer_id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Marker u WHERE u.layer_id = $layer_id")
            ->getResult();
    }

    public function findAllJson(){
        return 
            json_encode(
            $this->getEntityManager()
            ->createQuery("SELECT u.avatar,u.description,u.vip,u.status,c.url,l.icon,c.name company_name,c.phone,c.lat,c.lng  FROM AppBundle:Marker u INNER JOIN AppBundle:Company c WITH u.company_id = c.id INNER JOIN AppBundle:Layer l WITH c.layer_id = l.id")
            ->getArrayResult(), true);
    }
    
    public function findOneById($id)
    {
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Marker u WHERE u.id=$id")
            ->getOneOrNullResult();
    }

    public function findOneByCompanyId($company_id)
    {
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Marker u WHERE u.company_id=$company_id")
            ->getOneOrNullResult();
    }
    
    public function findOneByLatLng($lat, $lng)
    {
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Marker u WHERE u.lat=$lat AND u.lng=$lng")
            ->getOneOrNullResult();
    }
    
}
