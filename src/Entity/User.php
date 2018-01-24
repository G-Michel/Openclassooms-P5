<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("mail")
 * @UniqueEntity("username")
 */
class User implements UserInterface, AdvancedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(name="surname", type="string", length=255, nullable=true)
     */
    private $surname;

    /**
     * @ORM\Column(name="user_name", type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir un nom d’utilisateur")
     * @Assert\Type(
     *      type    = "string",
     *      message = "Vous devez saisir une chaine de caractère"
     * )
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage ="Vous devez entrer au moins {{ limit }} caractères",
     *      maxMessage ="Vous devez entrer moins de {{ limit }} caractères"
     * )
     */
    private $username;

    /**
     * @ORM\Column(name="role", type="array")
     */
    private $roles;

    /**
     * @ORM\Column(name="mail", type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir votre mail")
     * @Assert\Email(
     *      message = "L’email {{ value }} n’est pas un mail valide",
     *      checkMX = true
     * )
     */
    private $mail;

    /**
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(
     *      type    = "string",
     *      message = "Vous devez saisir une chaine de caractère"
     * )
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage ="Vous devez entrer au moins {{ limit }} caractères",
     *      maxMessage ="Vous devez entrer moins de {{ limit }} caractères"
     * )
     */
    private $plainPassword;

    /**
     * @ORM\Column(name="newsletter", type="boolean", nullable=true)
     * @Assert\Type(type = "bool")
     */
    private $newsletter;

    /**
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
    */
    private $salt;

    /**

     * Unidirectionnal - One User has One Picture . (OWNED SIDE)
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Picture", cascade={"persist"})
     * @Assert\Valid()
     *
     */
    private $picture;

    /**
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * Unidirectionnal - One User has One Auth . (OWNED SIDE)
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Auth", cascade={"persist"})
     * @Assert\Valid()
     *
     */
    private $auth;

    /**
    *
    * @ORM\Column(name="o_auth_provider", type="boolean", nullable=true)
    */
    private $oAuthProvider;

    /**
    *
    * @ORM\Column(name="o_auth_user_id", type="string", nullable=true)
    */
    private $oAuthUserId;

    public function setOAuthProvider($value)
    {
        $this->oAuthProvider = $value;
    }

    public function getOAuthProvider()
    {
        return $this->oAuthProvider;
    }

    public function setOAuthUserId($value)
    {
        $this->oAuthUserId = $value;
    }

    public function getOAuthUserId()
    {
        return $this->oAuthUserId;
    }

    public function eraseCredentials()
    {
        unset($this->plainPassword);
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }


    //GETTERS SETTERS

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

        /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     *
     * @return self
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     *
     * @return self
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     *
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     *
     * @return self
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFunction()
    {

        $function = array_map(function($role) {
            if (in_array($role, ['ROLE_ADMIN'])) {
                return 'Administrateur';
            }
            if (in_array($role, ['ROLE_NATURALIST'])) {
                return 'Naturaliste';
            }
            if (in_array($role, ['ROLE_USER'])) {
                return 'Membre';
            }
        },$this->roles);

        return $function[0];
    }

    /**
     * @return mixed
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param mixed $mail
     *
     * @return self
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     *
     * @return self
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * @param mixed $newsletter
     *
     * @return self
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param mixed $salt
     *
     * @return self
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getPicture()
    {
      return $this->picture;
    }

    /**
     * @param object $picture
     *
     * @return self
     */
    public function setPicture(Picture $picture = null)
    {
      $this->picture = $picture;
    }

        public function getAuth()
    {
      return $this->auth;
    }

    /**
     * @param object $auth
     *
     * @return self
     */
        public function setAuth(Auth $auth = null)
    {
      $this->auth= $auth;
    }
}

