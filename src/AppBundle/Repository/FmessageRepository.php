<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Category;

class FmessageRepository extends EntityRepository{   

    public function findAll(){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:Fmessage u")
            ->getResult();
    }

    public function findAllByForumId($forum_id){
        return 
            $this->getEntityManager()
            ->createQuery("SELECT u.message as forum_message,f.fio as userfio,u.created as message_date FROM AppBundle:Fmessage u JOIN AppBundle:User f WITH u.user_id = f.id  WHERE u.forum_id=$forum_id")
            ->getResult();
    }
}
