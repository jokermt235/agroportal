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

    public function getFio(){
        return $this->fio;
    }

    /**
    * @ORM\Column(type="string", length=80)
    */
    private $username;

    public function setUsername($user_name){
        $this->username = $user_name;
    }
   
    /**
    * @ORM\Column(type="string", length=128)
    */
    private $token;

    public function setToken($token){
        $this->token = $token;
    }

    public function getToken(){
        return $this->token;
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
    
    public function getPhone(){
        return $this->phone;
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

    private $city_id;

    public function setCityId($city_id){
        $this->city_id = $city_id;
    }

    public function getCityId(){
        return $this->city_id;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /**
    * @ORM\Column(type="string", length=255)
    */

    private $roles;

    public function setRoles($roles){
        $this->roles = $roles;
    }

    public function getRoles()
    {
        return array('ROLE_USER',$this->roles);
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
    }

}
