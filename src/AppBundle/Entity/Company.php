<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

    /**
    * @ORM\Entity(repositoryClass="AppBundle\Repository\CompanyRepository")
    * @ORM\Table(name="company")
    */
class Company
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
    private $name;
    
    public function setName($name){
        $this->name = $name;
    }

    public function getName(){
        return $this->name;
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
    * @ORM\Column(type="integer")
    */
    private $country_id;

    public function setCountryId($country_id){
        $this->country_id = $country_id;
    }

    /**
    * @ORM\Column(type="string", length=100)
    */
    private $country;
    
    public function setCountry($country){
        $this->country = $country;
    }

    public function getCountry(){
        return $this->country;
    }

    /**
    * @ORM\Column(type="string", length=255)
    */
    private $url;
    
    public function setUrl($url){
        $this->url = $url;
    }

    public function getUrl(){
        return $this->url;
    }

    public function getCountryId(){
        return $this->country_id;
    }

    /**
    * @ORM\Column(type="string", length=50)
    */
    private $phone;

    public function setPhone($phone){
        $this->phone = $phone;
    }
    
    public function getPhone(){
        return $this->phone;
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
    * @ORM\Column(type="string")
    */
    private $created;

    public function getCreated(){
        return $this->created;
    }

    /**
    * @ORM\Column(type="text")
    */
    private $avatar;    

    public function getAvatar(){
        return $this->avatar;
    }
        
    public function setAvatar($avatar){
        $this->avatar = $avatar;
    }

    public function getId(){
        return $this->id;
    }
   
}
