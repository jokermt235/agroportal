<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

    /**
    * @ORM\Entity(repositoryClass="AppBundle\Repository\CityRepository")
    * @ORM\Table(name="cities")
    */
class City
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
    private $region_id;
    
    public function setRegionId($region_id){
        $this->region_id = $region_id;
    }

    public function getRegionId(){
        return $this->region_id;
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
}
