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
     * @ORM\Column(name="bird_number", type="integer")
     * @Assert\NotBlank(
     *     groups = {"step3"},
     *     message = "Vous devez saisir un nombre"
     * )
     * @Assert\Type(
     *     type    = "numeric",
     *     message = "Vous devez saisir un nombre entier"
     * )
     * @Assert\GreaterThanOrEqual(
     *     value   = 1,
     *     message = "Le nombre d’oiseau doit être supérieur ou égale à {{ compared_value }}"
     * )
     * @Assert\LessThanOrEqual(
     *     value   = 20,
     *     message = "Le nombre d’oiseau doit être inférieur ou égale à {{ compared_value }}"
     * )
     */
    private $birdNumber;

    /**
     * @ORM\Column(name="bird_size", type="integer")
     * @Assert\NotBlank(
     *     groups = {"step3"},
     *     message = "Vous devez saisir une taille"
     * )
     * @Assert\Type(
     *     type    = "int"
     * )
     */
    private $birdSize;

    /**
     * @ORM\Column(name="bird_colors", type="array")
     * @Assert\NotBlank(
     *     groups = {"step3"},
     *     message = "Vous devez sélectionner au moins une couleur"
     * )
     * @Assert\Type(
     *     type    = "array"
     * )
     */
    private $birdColors;

    /**
     * Unidirectionnal - Many Bird has One Taxref . (OWNED SIDE)
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Taxref")
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
    public function getBirdNumber()
    {
        return $this->birdNumber;
    }

    /**
     * @param mixed $birdNumber
     *
     * @return self
     */
    public function setBirdNumber($birdNumber)
    {
        $this->birdNumber = $birdNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBirdSize()
    {
        $sizeCode = [1, 2, 3, 4];
        $sizeDef = ['Très grand', 'Grand', 'Petit', 'Très petit'];

        return str_replace($sizeCode, $sizeDef,$this->birdSize );
    }

    /**
     * @param mixed $birdSize
     *
     * @return self
     */
    public function setBirdSize($birdSize)
    {
        $this->birdSize = $birdSize;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBirdColors()
    {
        return $this->birdColors;
    }
    /**
     * @return mixed
     */
    public function getBirdColorsToString()
    {
        return implode(', ', $this->getBirdColors());
    }

    /**
     * @param mixed $birdColors
     *
     * @return self
     */
    public function setBirdColors($birdColors)
    {
        $this->birdColors = $birdColors;

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
