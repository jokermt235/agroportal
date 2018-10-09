<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

    /**
    * @ORM\Entity(repositoryClass="AppBundle\Repository\SidebannerRepository")
    * @ORM\Table(name="side_banners")
    */

class Sidebanner
{
    /**
    * @ORM\Column(type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

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
    private $show_count;
    
    public function setShowCount($show_count){
        $this->show_count += 1;
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
    private $images;    

    public function getImages(){
        return $this->images;
    }
        
    public function setImages($images){
        $this->images = $images;
    }

    public function getId(){
        return $this->id;
    }
   
}
