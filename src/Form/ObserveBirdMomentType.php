<?php

namespace App\Form;

use App\Entity\ObserveBirdMoment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//use pour les champs de formulaire
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ObserveBirdMomentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateObs', DateTimeType::class, [
                'widget'     => 'single_text',
                'html5' => false,
                'format' => 'dd-MM-yyyy H:m',
                'attr' => ['class' => 'js-datepicker'],
                'invalid_message' => "Vous devez saisir une date et une heure valide"
            ])
            ->add('comment', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            //'data_class' => ObserveBirdMoment::class,
        ]);
    }
}
