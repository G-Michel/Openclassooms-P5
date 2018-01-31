<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
 * 
 */
class Notification
{
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
	* @ORM\ManyToOne(targetEntity="App\Entity\User")
	*/
	private $from;


	public function __construct(User $from,User $to,$status)
	{
		$this->setStatus($status);
		$this->setFrom($from);
		$this->setTo($to);
		$this->seen = 0;
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
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $from
     *
     * @return self
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }
}