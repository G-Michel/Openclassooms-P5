<?php

namespace App\Entity;

use App\Entity\Bird;
use App\Entity\User;
use App\Entity\Picture;
use App\Entity\Location;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ObservationRepository")
 * @ORM\HasLifecycleCallbacks()
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
     * @Assert\NotBlank(
     *      groups = {"step2"},
     *      message="Vous devez saisir une date et une heure"
     * )
     * @Assert\DateTime(
     *      format = "dd-MM-yyyy H:m",
     *      message     = "Vous devez saisir une date et une heure valide entité"
     * )
     */
    private $dateObs;

    /**
     * @ORM\Column(name="date_add", type="datetime", nullable=true)
     */
    private $dateAdd;

    /**
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(name="comment", type="string", length=255, nullable=true)
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
     * @Assert\Valid()
     */
    private $location;

    /**
     * Unidirectionnal - One Observation has One Bird . (OWNED SIDE)
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Bird", cascade={"persist"}, inversedBy="observation")
     * @Assert\NotBlank(
     *     groups = {"step3"},
     *     message = "Vous devez sélectionner un oiseau"
     * )
     * @Assert\Valid()
     *
     */
    private $bird;

    /**
     * Unidirectionnal - One Observation has One Picture . (OWNED SIDE)
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Picture", cascade={"persist", "remove"})
     *
     */
    private $picture;

    /**
     * Unidirectionnal - Many Observation has One User . (OWNED SIDE)
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @Assert\Valid()
     *
     */
    private $user;

    /**
     * @ORM\PrePersist
     */
    public function setDateAddValue()
    {
        $this->dateAdd = new \DateTime();
    }
    /**
     * @ORM\PrePersist
     */
    public function setStatusValue()
    {
        if ($this->status === null) {
            $this->status = 0;
        }
    }
    /**
     * @ORM\PrePersist
     */
    public function hasPicture()
    {
        if ($this->getPicture() && $this->getPicture()->getFile() === null) {
            # code...
            $this->setPicture(null);
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
    public function getLocation()
    {
      return $this->location;
    }

    /**
     * @param object $location
     *
     * @return self
     */
    public function setLocation(Location $location = null)
    {
      $this->location = $location;
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
    public function setPicture(Picture $picture = null)
    {
        $this->picture = $picture;
    }

    /**
     * @return object
     */
    public function getUser()
    {
      return $this->user;
    }

    /**
     * @param object $user
     *
     * @return self
     */
    public function setUser(User $user = null)
    {
      $this->user = $user;
    }

    public function getAgo($datetime, $full = false) {
        $now = new \DateTime;
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'an',
            'm' => 'mois',
            'w' => 'sem',
            'd' => 'jr',
            'h' => 'hr',
            'i' => 'min',
            's' => 'sec',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 && !in_array($k,['m','w','i','s']) ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? ' il y a ' . implode(', ', $string) : 'maintenant';
    }
}
