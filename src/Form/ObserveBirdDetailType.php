<?php

namespace App\Form;

use App\Entity\Bird;
use App\Form\ObservationType;
use App\Entity\ObserveBirdDetail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObserveBirdDetailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('location')
            ->remove('dateObs')
            ->remove('comment')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            //'data_class' => ObserveBirdDetail::class,
            'validation_groups' =>  array('step3', 'Default'),
        ]);
    }
    public function getParent()
    {
      return ObservationType::class;
    }
}
