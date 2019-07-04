<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

    /**
    * @ORM\Entity(repositoryClass="AppBundle\Repository\MarkerRepository")
    * @ORM\Table(name="marker")
    */

class Marker
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
    private $name;
    
    public function setName($name){
        $this->name = $name;
    }

    public function getName(){
        return $this->name;
    }

    /**
    * @ORM\Column(type="string")
    */
    private $added;

    public function getAdded(){
        return $this->added;
    }

    /**
    * @ORM\Column(type="integer")
    */
    private $company_id;

    public function getCompanyId(){
        return $this->company_id;
    }
        
    public function setCompanyId($company_id){
        $this->company_id = $company_id;
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
    
    /**
    * @ORM\Column(type="string", length=20)
    */
    private $status;

    public function getStatus(){
        return $this->status;
    }
        
    public function setStatus($status){
        $this->status = $status;
    }

    /**
    * @ORM\Column(type="string", length=255)
    */
    private $avatar;

    public function getAvatar(){
        return $this->avatar;
    }
        
    public function setAvatar($avatar){
        $this->avatar = $avatar;
    }

    /**
    * @ORM\Column(type="string", length=255)
    */
    private $description;

    public function getDescription(){
        return $this->description;
    }
        
    public function setDescription($description){
        $this->description = $description;
    }

    /**
    * @ORM\Column(name="vip", type="boolean")
    */
    private $vip = false;

    public function getVip()
    {
        return $this->vip;
    }
}
