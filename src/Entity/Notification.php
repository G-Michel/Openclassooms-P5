<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Observation;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
 * 
 */
class Notification
{
	/** @var array Map of standard OBSERVATION status code/reason phrases */
    private static $phrases = [
        1  => 'En ligne',
        0    => 'Validation en cours',
        -100 => 'En attente',
        -200 => 'Validation refusée',
        -201 => 'Validation refusée, Vérifer la région de l\'observation',
        -202 => 'Validation refusée, Vérifer le moment de l\'observation',
        -203 => 'Validation refusée, Vérifer les couleurs de l\'oiseau observé',
        -204 => 'Validation refusée, Vérifer la taille de l\'oiseau observé',
        -205 => 'Validation refusée, Vérifer la photo de l\'oiseau observé'
    ];

	/**
	* @ORM\Id
	* @ORM\GeneratedValue
	* @ORM\Column(type="integer")
	*/
	private $id;

	/**
	* @ORM\Column(type="boolean", name="seen")
	* @Assert\NotBlank()
	*/
	private $seen;

	/**
	* @ORM\Column(type="integer", name="status")
	*/
	private $status;

	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\User")
	* 
	*/
	private $to;

	/**
	*
	* @ORM\Column(type="string", name="fromUser")
	*/
	private $fromUser;

    /**
    *
    * @ORM\Column(type="string", name="birdName")
    */
    private $birdName;

	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Observation")
	* 
	*/
	private $observation;


	public function __construct(Observation $observation,$from)
	{
		$this->setStatus($observation->getStatus());
		$this->fromUser= $from;
		$this->setTo($observation->getUser());
		$this->observation = $observation;
		$this->seen = 0;
        $this->birdName = $observation->getBird()->getTaxref()->getNomVernType();
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
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     * @param mixed $seen
     *
     * @return self
     */
    public function setSeen($seen)
    {
        $this->seen = $seen;

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
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     *
     * @return self
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFromUser()
    {
        return $this->fromUser;
    }

    /**
     * @param mixed $from
     *
     * @return self
     */
    public function setFromUser($fromUser)
    {
        $this->fromUser = $fromUser;

        return $this;
    }

        public function getStatusDefinition()
    {
        return self::$phrases[$this->getStatus()];
    }

    /**
     * @return mixed
     */
    public function getObservation()
    {
        return $this->observation;
    }

    /**
     * @param mixed $to
     *
     * @return self
     */
    public function setObservation($observation)
    {
        $this->observation = $observatrion;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBirdName()
    {
        return $this->birdName;
    }
}