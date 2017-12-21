<?php

namespace App\Entity;

use App\Entity\Bird;
use App\Entity\Picture;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ObservationRepository")
 */
class Observation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="date_obs", type="datetime")
     * @Assert\NotBlank(message="Vous devez saisir une date et une heure")
     * @Assert\DateTime(
     *      format = "dd-MM-yyyy H:m",
     *      message     = "Vous devez saisir une date et une heure valide entité"
     * )
     */
    private $dateObs;

    /**
     * @ORM\Column(name="date_add", type="datetime")
     */
    private $dateAdd;

    /**
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(name="bird_number", type="integer")
     */
    private $birdNumber;

    /**
     * @ORM\Column(name="comment", type="string", length=255)
     * @Assert\Type(
     *      type    = "string",
     *      message = "Vous devez saisir une chaine de caractère"
     * )
     * @Assert\Length(
     *      max        = 255,
     *      maxMessage = "Vous devez entrer moins de {{ limit }} caractères"
     * )
     */
    private $comment;

    /**
     * Unidirectionnal - One Observation has One Location . (OWNED SIDE)
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Location", cascade={"persist"})
     */
    private $location;

    /**
     * Unidirectionnal - One Observation has One Bird . (OWNED SIDE)
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Bird", cascade={"persist"})
     *
     */
    private $bird;

    /**
     * Unidirectionnal - One Observation has One Picture . (OWNED SIDE)
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Picture", cascade={"persist"})
     *
     */
    private $picture;

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
    public function getDateObs()
    {
        return $this->dateObs;
    }

    /**
     * @param mixed $dateObs
     *
     * @return self
     */
    public function setDateObs($dateObs)
    {
        $this->dateObs = $dateObs;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateAdd()
    {
        return $this->dateAdd;
    }

    /**
     * @param mixed $dateAdd
     *
     * @return self
     */
    public function setDateAdd($dateAdd)
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     *
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBirdNumber()
    {
        return $this->birdNumber;
    }

    /**
     * @param mixed $birdNumber
     *
     * @return self
     */
    public function setBirdNumber($birdNumber)
    {
        $this->birdNumber = $birdNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     *
     * @return self
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return object
     */
    public function getBird()
    {
      return $this->bird;
    }

    /**
     * @param object $bird
     *
     * @return self
     */
    public function setBird(Bird $bird = null)
    {
      $this->bird = $bird;
    }

    /**
     * @return object
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
    public function setPicture(UploadedFile $picture = null)
    {
      $this->picture = $picture;
    }
}
