<?php

namespace App\Form;

use App\Entity\Bird;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BirdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('birdNumber', RangeType::class, [
                'label'      => 'Nombre d\'oiseaux',
                'label_attr' => ['id'  => "birdNumber"],
                'attr'       => ['min' => "1", 'max' => "20", 'class' => "slider"]
            ])
            ->add('birdSize', ChoiceType::class, [
              'label'   => 'Taille de l\'oiseau',
              'choices' => [
                  'XL' => 1,
                  'L'  => 2,
                  'M'  => 3,
                  'S'  => 4,
              ],
            ])
            ->add('birdColors', ChoiceType::class, [
              'label'   => 'Couleur de 1 à 3',
              'choices' => [
                'noir'   => 'noir',
                'beige'  => 'beige',
                'blanc'  => 'blanc',
                'marron' => 'marron',
                'rouge'  => 'rouge',
                'jaune'  => 'jaune',
                'vert'   => 'vert',
                'bleu'   => 'bleu',
                'orange' => 'orange',
              ],
              'multiple' => true,
              'expanded' => true,
            ])
      ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => Bird::class,
        ]);
    }
}
