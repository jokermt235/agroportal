<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

    /**
    * @ORM\Entity(repositoryClass="AppBundle\Repository\LayerRepository")
    * @ORM\Table(name="layer")
    */

class Layer
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
    * @ORM\Column(type="text")
    */
    private $icon;

    public function getIcon(){
        return $this->icon;
    }
        
    public function setIcon($icon){
        $this->icon = $icon;
    }

    public function getId(){
        return $this->id;
    }
   
}
