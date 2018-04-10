<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

    /**
    * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
    * @ORM\Table(name="category")
    */
class Category
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

    public function setPosotion($postion){
        $this->postion = $postion;
    }

    public function getPostion(){
        return $this->postion;
    }

    public function getId(){
        return $this->id;
    }

    
}
