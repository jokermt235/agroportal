<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

    /**
    * @ORM\Entity(repositoryClass="AppBundle\Repository\AdvertRepository")
    * @ORM\Table(name="advert")
    */
class Advert
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
    * @ORM\Column(type="integer")
    */
    private $price;

    public function setPrice($price){
        $this->price = $price;
    }

    public function getPrice(){
        return $this->price;
    }

    /**
    * @ORM\Column(type="integer")
    */
    private $section_id;

    public function setSectionId($section_id){
        $this->section_id = $section_id;
    }

    public function getSectionId(){
        return $this->section_id;
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
    private $city_id;

    public function setCityId($city_id){
        $this->city_id = $city_id;
    }

    public function getCityId(){
        return $this->city_id;
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
    * @ORM\Column(type="string")
    */
    private $created;

    public function getCreated(){
        return $this->created;
    }

    /**
    * @ORM\Column(type="string")
    */
    private $avatar;    

    public function getAvatar(){
        return $this->avatar;
    }
        
    public function setAvatar($avatar){
        $this->avatar = $avatar;
    }

    /**
    * @ORM\Column(type="integer")
    */
    private $views;    

    public function getViews(){
        return $this->views;
    }
        
    public function setViews(){
        $this->views += 1;
    }

    public function getId(){
        return $this->id;
    }

   /**
    * @ORM\Column(type="integer")
    */
    private $status;    

    public function getStatus(){
        return $this->status;
    }
        
    public function setStatus($status){
        $this->status = $status;
    }

    /**
    * @ORM\Column(type="string")
    */
    private $currency;    

    public function getCurrency(){
        return $this->currency;
    }
        
    public function setCurrency($currency){
        $this->currency = $currency;
    }

    /**
    * @ORM\Column(type="integer")
    */
    private $company_id;

    public function setCompanyId($company_id){
        $this->company_id = $company_id;
    }

    public function getCompanyId(){
        return $this->company_id;
    }

    public function getcompany_id(){
        return $this->company_id;
    }
}
