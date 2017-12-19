<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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

   
}
