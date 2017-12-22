<?php

namespace App\Form;

use App\Form\ObservationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObserveBirdLocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('dateObs')
            ->remove('comment')
            ->remove('bird')
            ->remove('birdNumber')
            ->remove('picture')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            //'data_class' => ObserveBirdLocation::class,
        ]);
    }
    public function getParent()
    {
      return ObservationType::class;
    }
}
