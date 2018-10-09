<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

    /**
    * @ORM\Entity(repositoryClass="AppBundle\Repository\CurrencyRepository")
    * @ORM\Table(name="currency")
    */
class Currency
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
    * @ORM\Column(type="string", length=100)
    */
    private $code;
    
    public function setCode($code){
        $this->code = $code;
    }

    public function getCode(){
        return $this->code;
    }

    /**
    * @ORM\Column(type="string", length=100)
    */
    private $symbol;
    
    public function setSymbol($symbol){
        $this->symbol = $symbol;
    }

    public function getSymbol(){
        return $this->symbol;
    }
}

