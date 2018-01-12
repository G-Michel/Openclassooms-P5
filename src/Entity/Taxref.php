<?php

namespace App\Entity;

use App\Entity\Picture;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\TaxrefRepository")
 */
class Taxref
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     *
     * See https://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
     */
    const NUM_ITEMS = 100;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="reign_type", type="string", length=255)
     */
    protected $reignType;

    /**
     * @ORM\Column(name="phylum_type", type="string", length=255)
     */
    protected $phylumType;

    /**
     * @ORM\Column(name="nomVern_type", type="string", length=255)
     */
    protected $nomVernType;

    /**
     * @ORM\Column(name="nomValide_type", type="string", length=255)
     */
    protected $nomValideType;

    /**
     * Unidirectionnal - One Taxref has One Picture . (OWNED SIDE)
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Picture", cascade={"persist"})
     * @Assert\Valid()
     *
     */
    private $picture;

    /**
     * @ORM\Column(name="class_type", type="string", length=255)
     */
    protected $classType;

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
    public function getReignType()
    {
        return $this->reignType;
    }

    /**
     * @param mixed $reignType
     *
     * @return self
     */
    public function setReignType($reignType)
    {
        $this->reignType = $reignType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhylumType()
    {
        return $this->phylumType;
    }

    /**
     * @param mixed $phylumType
     *
     * @return self
     */
    public function setPhylumType($phylumType)
    {
        $this->phylumType = $phylumType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNomValideType()
    {
        return $this->nomValideType;
    }

    /**
     * @param mixed $nomValideType
     *
     * @return self
     */
    public function setNomValideType($nomValideType)
    {
        $this->nomValideType = $nomValideType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNomVernType()
    {
        return $this->nomVernType;
    }

    /**
     * @param mixed $nomVernType
     *
     * @return self
     */
    public function setNomVernType($nomVernType)
    {
        $this->nomVernType = $nomVernType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getClassType()
    {
        return $this->classType;
    }

    /**
     * @param mixed $classType
     *
     * @return self
     */
    public function setClassType($classType)
    {
        $this->classType = $classType;

        return $this;
    }

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

}
