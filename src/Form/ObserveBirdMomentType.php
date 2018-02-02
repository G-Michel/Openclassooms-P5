<?php

namespace App\Form;

use App\Form\ObservationType;
use App\Entity\ObserveBirdMoment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObserveBirdMomentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('location')
            ->remove('bird')
            ->remove('birdNumber')
            ->remove('picture')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            //'data_class' => ObserveBirdMoment::class,
            'validation_groups' =>  array('step2'),
        ]);
    }

    public function getParent()
    {
      return ObservationType::class;
    }

}
