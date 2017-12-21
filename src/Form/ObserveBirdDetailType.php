<?php

namespace App\Form;

use App\Entity\Bird;
use App\Entity\ObserveBirdDetail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

//use pour les champs de formulaire
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
//use pour les validations du formulaire
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class ObserveBirdDetailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bird', EntityType::class, [
                'class'        => Bird::class,
                'choice_label' => 'referenceName',
                'placeholder'  => "Choisissez parmi la liste d'oiseaux",
                'constraints'  => [
                    new NotBlank([
                        'message' => "Vous devez sélectionner un oiseau"
                    ]),
                ],
                'invalid_message' => "Vous devez choisir un oiseau parmi la liste"

            ])
            ->add('birdNumber', IntegerType::class, [
                'attr' => [
                    'min' => "1",
                    'max' => "20"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Vous devez saisir un nombre"
                    ]),
                    new Type([
                        'type'    => 'int',
                        'message' => "Vous devez saisir un nombre entier"
                    ]),
                    new GreaterThanOrEqual([
                        'value'   => 1,
                        'message' => "Le nombre d’oiseau doit être supérieur ou égale à {{ compared_value }}"
                    ]),
                    new LessThanOrEqual([
                        'value'   => 20,
                        'message' => "Le nombre d’oiseau doit être inférieur ou égale à {{ compared_value }}"
                    ])
                ]
            ])
            ->add('picture', FileType::class, [
                'required'    => false,
                'constraints' => [
                    new Image([
                        'minWidth'  => 200,
                        'maxWidth'  => 400,
                        'minHeight' => 200,
                        'maxHeight' => 400,
                        'mimeTypesMessage' => "Le fichier n’est pas une image valide",
                        'minWidthMessage'  => "La largeur de l'image est trop petite ({{ width }} px), La largeur minimum autorisée est {{ min_width }} px",
                        'maxWidthMessage'  => "La largeur de l'image est trop grande ({{ width }} px), La largeur maximum autorisée est {{ max_width }} px",
                        'minHeightMessage' => "La hauteur de l'image est trop petite ({{ width }} px), La hauteur minimum autorisée est {{ min_width }} px",
                        'maxHeightMessage' => "La hauteur de l'image est trop grande ({{ width }} px), La hauteur maximum autorisée est {{ max_width }} px"
                    ]),
                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            //'data_class' => ObserveBirdDetail::class,
        ]);
    }
}
