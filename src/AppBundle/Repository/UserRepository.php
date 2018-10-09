<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository{   
    public function findOneByUsername($username){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:User u WHERE u.username='$username'")
            ->getResult();
    }

    public function findOneById($id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:User u WHERE u.id='$id'")
            ->setMaxResults(1)
            ->getResult()[0];
    }
    
    public function findOneEmail($email){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:User u WHERE u.email = '$email'")
            ->getResult();
    }

    public function findUserByToken($token){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:User u WHERE u.token = '$token'")
            ->getOneOrNullResult();
    }
}
