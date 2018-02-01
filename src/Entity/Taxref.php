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
    const NUM_ITEMS = 10;
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
     * @ORM\Column(name="class_type", type="string", length=255)
     */
    protected $classType;

    /**
     * @ORM\Column(name="cdNom_type", type="integer")
     */
    protected $cdNomType;

    /**
     * @ORM\Column(name="lbNom_type", type="string", length=255)
     */
    protected $lbNomType;

    /**
     * @ORM\Column(name="lbAuteur_type", type="string", length=255)
     */
    protected $lbAuteurType;

    /**
     * @ORM\Column(name="nomValide_type", type="string", length=255)
     */
    protected $nomValideType;

    /**
     * @ORM\Column(name="nomVern_type", type="string", length=255)
     */
    protected $nomVernType;

    /**
     * @ORM\Column(name="fr_type", type="string", length=255)
     */
    protected $frType;

    /**
     * @ORM\Column(name="slug", type="string", length=255)
     */
    protected $slug;

    /**
     * Unidirectionnal - One Taxref has One Picture . (OWNED SIDE)
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Picture", cascade={"persist"})
     * @Assert\Valid()
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

    /**
     * @return mixed
     */
    public function getCdNomType()
    {
        return $this->cdNomType;
    }

    /**
     * @param mixed $cdNomType
     *
     * @return self
     */
    public function setCdNomType($cdNomType)
    {
        $this->cdNomType = $cdNomType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLbNomType()
    {
        return $this->lbNomType;
    }

    /**
     * @param mixed $lbNomType
     *
     * @return self
     */
    public function setLbNomType($lbNomType)
    {
        $this->lbNomType = $lbNomType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLbAuteurType()
    {
        return $this->lbAuteurType;
    }

    /**
     * @param mixed $lbAuteurType
     *
     * @return self
     */
    public function setLbAuteurType($lbAuteurType)
    {
        $this->lbAuteurType = $lbAuteurType;

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
    public function getFrType()
    {
        return $this->frType;
    }

    /**
     * @param mixed $frType
     *
     * @return self
     */
    public function setFrType($frType)
    {
        $this->frType = $frType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     *
     * @return self
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return mixed
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

}
