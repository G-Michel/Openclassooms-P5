<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(name="surname", type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\Column(name="user_name", type="string", length=255)
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
    private $userName;

    /**
     * @ORM\Column(name="role", type="string", length=255)
     */
    private $role;

    /**
     * @ORM\Column(name="mail", type="string", length=255)
     * @Assert\NotBlank(message="Vous devez saisir votre mail")
     * @Assert\Email(
     *      message = "L’email {{ value }} n’est pas un mail valide",
     *      checkMX = true
     * )
     */
    private $mail;

    /**
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\NotBlank(message="Vous devez saisir un mot de passe")
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
    private $password;

    /**
     * @ORM\Column(name="newsletter", type="boolean")
     * @Assert\Type(type = "bool")
     */
    private $newsletter;

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
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param mixed $userName
     *
     * @return self
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     *
     * @return self
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
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
     * @param boolean $newsletter
     *
     * @return self
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isNewsletter()
    {
        return $this->newsletter;
    }


}
