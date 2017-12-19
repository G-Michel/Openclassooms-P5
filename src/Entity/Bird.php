<?php

namespace App\Entity;

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
     * @ORM\Column(name="reference_id", type="integer")
     */
    private $referenceId;

    /**
     * @ORM\Column(name="reference_name", type="string", length=255)
     */
    private $referenceName;

    /**
     * @ORM\Column(name="vernicular_name", type="string", length=255)
     */
    private $vernicularName;

    /**
     * @ORM\Column(name="inpn_link", type="string", length=255)
     */
    private $inpnLink;



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
    public function getReferenceId()
    {
        return $this->referenceId;
    }

    /**
     * @param mixed $referenceId
     *
     * @return self
     */
    public function setReferenceId($referenceId)
    {
        $this->referenceId = $referenceId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReferenceName()
    {
        return $this->referenceName;
    }

    /**
     * @param mixed $referenceName
     *
     * @return self
     */
    public function setReferenceName($referenceName)
    {
        $this->referenceName = $referenceName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVernicularName()
    {
        return $this->vernicularName;
    }

    /**
     * @param mixed $vernicularName
     *
     * @return self
     */
    public function setVernicularName($vernicularName)
    {
        $this->vernicularName = $vernicularName;

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
}
