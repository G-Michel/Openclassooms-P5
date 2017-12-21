<?php

namespace App\Form;

use App\Entity\SignIn;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

//use pour les chams de formulaire
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
//use pour les validations du formulaire
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class SignInType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, [
                'mapped'   => false,
                'constraints' => [
                    new NotBlank([
                        'message' => "Vous devez saisir votre nom d’utilisateur ou votre mail"
                    ]),
                    new Type([
                        'type' => 'string',
                        'message' => "Vous devez saisir votre nom d’utilisateur ou votre mail"
                    ])
                ]
            ])
            ->add('password', PasswordType::class, [
                'mapped'   => false,
                'constraints' => [
                    new NotBlank([
                        'message' => "Vous devez saisir votre mot de passe"
                    ]),
                    new Type([
                        'type' => 'string',
                        'message' => "Vous devez saisir votre mot de passe"
                    ])
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            //'data_class' => SignIn::class,
        ]);
    }
}
