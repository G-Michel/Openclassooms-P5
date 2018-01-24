<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 */
class Location
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="gps_x", type="decimal",precision=7, scale=5)
     * @Assert\NotBlank(message="Vous devez saisir une longitude")
     * @Assert\Type(
     *      type    = "float",
     *      message = "Vous devez saisir une longitude valide"
     * )
     */
    private $gpsX;

    /**
     * @ORM\Column(name="gps_y", type="decimal",precision=7, scale=5)
     * @Assert\NotBlank(message="Vous devez saisir une latitude")
     * @Assert\Type(
     *      type    = "float",
     *      message = "Vous devez saisir une latitude valide"
     * )
     */
    private $gpsY;

    /**
     * @ORM\Column(name="address", type="string", length=255)
     * @Assert\Type(
     *      type    = "string",
     *      message = "Vous devez saisir une chaine de caractère"
     * )
     */
    private $address;

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
    public function getGpsX()
    {
        return $this->gpsX;
    }

    /**
     * @param mixed $gpsX
     *
     * @return self
     */
    public function setGpsX($gpsX)
    {
        $this->gpsX = $gpsX;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGpsY()
    {
        return $this->gpsY;
    }

    /**
     * @param mixed $gpsY
     *
     * @return self
     */
    public function setGpsY($gpsY)
    {
        $this->gpsY = $gpsY;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     *
     * @return self
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }
}
