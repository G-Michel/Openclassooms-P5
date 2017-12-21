<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//use pour les champs de formulaire
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ObserveBirdLocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gpsX', NumberType::class, [
                'invalid_message' => "Vous devez saisir une longitude valide"
            ])
            ->add('gpsY', NumberType::class, [
                'invalid_message' => "Vous devez saisir une latitude valide"
            ])
            ->add('address', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            //'data_class' => ObserveBirdLocation::class,
        ]);
    }
}
