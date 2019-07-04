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
    private $views;

    public function setViews(){
        $this->views += 1;
    }

    public function getViews(){
        return $this->views;
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
    

    /**
    * @ORM\Column(type="string", length=50)
    */
    private $phone_code;

    public function setPhoneCode($phone_code){
        $this->phone_code = $phone_code;
    }
    
    public function getPhoneCode(){
        return $this->phone_code;
    }

    /**
    * @ORM\Column(type="string", length=50)
    */
    private $phone_without_code;

    public function setPhoneWithoutCode($phone_without_code){
        $this->phone_without_code = $phone_without_code;
    }
    
    public function getPhoneWithoutCode(){
        return $this->phone_without_code;
    }

    /**
    * @ORM\Column(type="string", length=255)
    */
    private $contact_name;

    public function setContactName($contact_name){
        $this->contact_name = $contact_name;
    }
    
    public function getContactName(){
        return $this->contact_name;
    }

    /**
    * @ORM\Column(type="string", length=255)
    */
    private $lat;

    public function getLat(){
        return $this->lat;
    }
        
    public function setLat($lat){
        $this->lat = $lat;
    }

    /**
    * @ORM\Column(type="string", length=255)
    */
    private $lng;

    public function getLng(){
        return $this->lng;
    }
        
    public function setLng($lng){
        $this->lng = $lng;
    }

    /**
    * @ORM\Column(type="integer")
    */
    private $layer_id;

    public function getLayerId(){
        return $this->layer_id;
    }
        
    public function setLayerId($layer_id){
        $this->layer_id = $layer_id;
    }
}
