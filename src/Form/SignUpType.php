<?php

namespace App\Form;

use App\Entity\SignUp;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

//use pour les chams de formulaire
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
//use pour les validations du formulaire
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\IsTrue;

class SignUpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mail', EmailType::class)
            ->add('userName', TextType::class)
            ->add('password', PasswordType::class)
            ->add('newsletter',CheckboxType::class, [
                'required' => false
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped'   => false,
                'constraints' => [
                    new Type([
                        'type' => "bool"
                    ]),
                    new IsTrue([
                        'message' => "Les conditions doivent être acceptées"
                    ])
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            //'data_class' => SignUp::class,
        ]);
    }
}
