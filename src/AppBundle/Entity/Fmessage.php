<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

    /**
    * @ORM\Entity(repositoryClass="AppBundle\Repository\FmessageRepository")
    * @ORM\Table(name="forum_message")
    */
class Fmessage
{
    /**
    * @ORM\Column(type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    public function getId(){
        return $this->id;
    }

    /**
    * @ORM\Column(type="text")
    */
    private $message;
    
    public function setMessage($message){
        $this->message = $message;
    }

    public function getMessage(){
        return $this->message;
    }

    /**
    * @ORM\Column(type="integer")
    */
    private $category_id;
    
    public function setCategoryId($category_id){
        $this->category_id = $category_id;
    }

    public function getCategoryId(){
        return $this->category_id;
    }
    
    /**
    * @ORM\Column(type="integer")
    */
    
    private $forum_id;
    
    public function setForumId($forum_id){
        $this->forum_id = $forum_id;
    }

    public function getForumId(){
        return $this->forum_id;
    }   

    /**
    * @ORM\Column(type="integer")
    */
    
    private $user_id;
    
    public function setUserId($user_id){
        $this->user_id = $user_id;
    }

    public function getuser_id(){
        return $this->user_id;
    }
    
    /**
    * @ORM\Column(type="datetime")
    */
    
    private $created;

    public function geCreated(){
        return $this->created;
    }
}
