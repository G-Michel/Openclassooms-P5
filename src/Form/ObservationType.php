<?php

namespace App\Form;

use App\Entity\Bird;
use App\Entity\Taxref;
use App\Entity\Observation;
use App\Form\Type\DateTimePickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class ObservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('location', LocationType::class)
            ->add('dateObs', DateTimePickerType::class, [
                'label' => 'Observé le',
            ])
            ->add('comment', TextareaType::class)
            // ->add('bird', EntityType::class, [
            //     'class'           => Taxref::class,
            //     'choice_label'    => 'nomValideType',
            //     'placeholder'     => "Choisissez parmi la liste d'oiseaux",
            //     'invalid_message' => "Vous devez choisir un oiseau parmi la liste"

            // ])
            ->add('bird', BirdType::class,[
                'label' => false,
            ])
            // ->add('birdNumber', IntegerType::class, [
            //     'attr' => ['min' => "1", 'max' => "20"]
            // ])
            ->add('picture', PictureType::class,[
                'label' => false,
                'attr' => ['class' => 'picture'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            //'data_class' => Observation::class,
            // 'validation_groups' => array(
            //     Observation::class,
            //     'determineValidationGroups',
            // ),
        ]);
    }
}
