<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AuthRepository")
 */
class Auth
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="remember_token", type="string", length=255)
     */
    private $rememberToken;
    
    /**
     * @ORM\Column(name="reset_at", type="datetime")
     */
    private $resetAt;

    /**
     * @ORM\Column(name="comfirmed_at", type="datetime")
     */
    private $comfirmedAt;

    /**
     * @ORM\Column(name="comfirmed_token", type="string", length=255)
     */
    private $comfirmedToken;

    /**
     * @ORM\Column(name="reset_token", type="string", length=255)
     */
    private $resetToken;

   

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
    public function getRememberToken()
    {
        return $this->rememberToken;
    }

    /**
     * @param mixed $rememberToken
     *
     * @return self
     */
    public function setRememberToken($rememberToken)
    {
        $this->rememberToken = $rememberToken;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResetAt()
    {
        return $this->resetAt;
    }

    /**
     * @param mixed $resetAt
     *
     * @return self
     */
    public function setResetAt($resetAt)
    {
        $this->resetAt = $resetAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getComfirmedAt()
    {
        return $this->comfirmedAt;
    }

    /**
     * @param mixed $comfirmedAt
     *
     * @return self
     */
    public function setComfirmedAt($comfirmedAt)
    {
        $this->comfirmedAt = $comfirmedAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getComfirmedToken()
    {
        return $this->comfirmedToken;
    }

    /**
     * @param mixed $comfirmedToken
     *
     * @return self
     */
    public function setComfirmedToken($comfirmedToken)
    {
        $this->comfirmedToken = $comfirmedToken;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResetToken()
    {
        return $this->resetToken;
    }

    /**
     * @param mixed $resetToken
     *
     * @return self
     */
    public function setResetToken($resetToken)
    {
        $this->resetToken = $resetToken;

        return $this;
    }
}
