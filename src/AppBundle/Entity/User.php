<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
    /**
    * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
    * @ORM\Table(name="users")
    */
class User implements UserInterface
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
    * @ORM\Column(type="string", length=80)
    */
    private $fio;

    public function setFio($user_fio){
        $this->fio = $user_fio;
    }

    /**
    * @ORM\Column(type="string", length=80)
    */
    private $username;

    public function setUsername($user_name){
        $this->username = $user_name;
    }
    
    /**
    * @ORM\Column(type="string", length=127)
    */
    private $password;
    
    public function setPassword($user_password){
        $this->password = $user_password;
    }

    public function getPassword(){
        return $this->password;
    }

    /**
    * @ORM\Column(type="string", length=30)
    */
    private $phone;

    public function setPhone($_phone){
        $this->phone = $_phone;
    }

    /**
    * @ORM\Column(type="string", length=30)
    */
    private $email;

    public function setEmail($_email){
        $this->email = $_email;
    }

    /**
    * @ORM\Column(type="text")
    */
    private $images;

    public function setImages($images){
        $this->images = $images;
    }
    
    public function getImages($images){
        return $this->images;
    }   
    
    /**
    * @ORM\Column(type="integer")
    */

    private $address_id;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    public function __construct()
    {
        $this->isActive = false;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid('', true));
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
    }

}
