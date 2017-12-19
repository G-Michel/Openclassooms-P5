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
     * @ORM\Column(name="gps_x", type="integer")
     */
    private $gpsX;

    /**
     * @ORM\Column(name="gps_y", type="integer")
     */
    private $gpsY;

    /**
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state;

    /**
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

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
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     *
     * @return self
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     *
     * @return self
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     *
     * @return self
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }
}
