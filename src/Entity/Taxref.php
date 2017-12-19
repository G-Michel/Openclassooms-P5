<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\TaxrefRepository")
 */
class Taxref
{
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
}
