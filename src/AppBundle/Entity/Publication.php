<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

    /**
    * @ORM\Entity(repositoryClass="AppBundle\Repository\PublicationRepository")
    * @ORM\Table(name="publication")
    */
class Publication
{
    /**
    * @ORM\Column(type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
    * @ORM\Column(type="string", length=100)
    */
    private $title;

    public function setTitle($title){
        $this->title = $title;
    }

    public function getTitle(){
        return $this->title;
    }

     /**
    * @ORM\Column(type="string", length=100)
    */
    private $url;
    
    public function setUrl($url){
        $this->url = $url;
    }

    public function getUrl(){
        return $this->url;
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

    /**
    * @ORM\Column(type="text")
    */
    private $images;

    public function setImages($images){
        $this->images = $images;
    }

    public function getImages(){
        return $this->images;
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

    public function getId(){
        return $this->id;
    }

    /**
    * @ORM\Column(type="string")
    */
    private $created;

    public function getCreated(){
        return $this->created;
    }
}
