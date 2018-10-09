<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

    /**
    * @ORM\Entity(repositoryClass="AppBundle\Repository\ForumRepository")
    * @ORM\Table(name="forum")
    */
class Forum
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
    * @ORM\Column(type="string", length=255)
    */
    private $title;
    
    public function setTitle($title){
        $this->title = $title;
    }

    public function getTitle(){
        return $this->title;
    }

    /**
    * @ORM\Column(type="string", length=127)
    */
    
    private $avatar;
    
    public function setAvatar($avatar){
        $this->avatar = $avatar;
    }

    public function getAvatar(){
        return $this->avatar;
    }

    /**
    * @ORM\Column(type="text")
    */
    private $description;
    
    public function setDescription($description){
        $this->description = $description;
    }

    public function getDescription(){
        return $this->description;
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
    
    private $country_id;
    
    public function setCountryId($country_id){
        $this->country_id = $country_id;
    }

    public function getCountryId(){
        return $this->country_id;
    }   

    /**
    * @ORM\Column(type="integer")
    */
    
    private $user_id;
    
    public function setUserId($user_id){
        $this->user_id = $user_id;
    }

    public function getUserId(){
        return $this->user_id;
    }

}
