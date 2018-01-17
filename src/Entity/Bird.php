<?php

namespace App\Entity;

use App\Entity\Taxref;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\BirdRepository")
 */
class Bird
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="slug", type="string", length=255)
     */
    protected $slug;

    /**
     * @ORM\Column(name="inpn_link", type="string", length=255)
     */
    private $inpnLink;

    /**
     * Unidirectionnal - Many Bird has One Taxref . (OWNED SIDE)
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Taxref", cascade={"persist"})
     * @Assert\Valid()
     *
     */
    private $taxref;

    /**
     * Bidirectionnal - One Bird has One Observation . (INVERSE SIDE)
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Observation", mappedBy="bird")
     *
     */
    private $observation;




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
    public function getslug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     *
     * @return self
     */
    public function setslug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInpnLink()
    {
        return $this->inpnLink;
    }

    /**
     * @param mixed $inpnLink
     *
     * @return self
     */
    public function setInpnLink($inpnLink)
    {
        $this->inpnLink = $inpnLink;

        return $this;
    }

    /**
     * @return object
     */
    public function getTaxref()
    {
        return $this->taxref;
    }

    /**
     * @param object Taxref
     *
     * @return self
     */
    public function setTaxref(Taxref $taxref = null)
    {
        $this->taxref = $taxref;

        return $this;
    }

    /**
     * @return object
     */
    public function getObservation()
    {
        return $this->observation;
    }

    /**
     * @param object Observation
     *
     * @return self
     */
    public function setObservation(Taxref $observation = null)
    {
        $this->observation = $observation;

        return $this;
    }

}
