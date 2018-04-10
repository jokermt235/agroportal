<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

    /**
    * @ORM\Entity
    * @ORM\Table(name="arecords")
    */
class Arecord
{
    /**
    * @ORM\Column(type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
    * @ORM\Column(type="string", length=50)
    */
    private $name;

    /**
    * @ORM\Column(type="string", length=250)
    */
    private $images;

    public function getId(){
        return $this->id;
    }

    public function setName($name){
        $this->name = $name;
    }
}
