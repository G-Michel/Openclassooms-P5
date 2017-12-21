<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

//use pour les champs de formulaire
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
//use pour les validations du formulaire
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Email;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mail', EmailType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => "Vous devez saisir un mail"
                    ]),
                    new Email([
                        'message' => "L’email {{ value }} n’est pas un mail valide",
                        'checkMX' => true
                    ])
                ]
            ])
            ->add('userName', TextType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => "Vous devez saisir un nom"
                    ]),
                    new Type([
                        'type' => 'string',
                        'message' => "Vous devez saisir une chaine de caractère"
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 50,
                        'minMessage' => "Vous devez entrer au moins {{ limit }} caractères",
                        'maxMessage' => "Vous devez entrer moins de {{ limit }} caractères"
                    ])
                ]
            ])
            ->add('message', TextareaType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => "Vous devez saisir un message"
                    ]),
                    new Type([
                        'type'    => 'string',
                        'message' => "Vous devez saisir une chaine de caractère"
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => "Vous devez entrer moins de {{ limit }} caractères"
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            //'data_class' => Contact::class,
        ]);
    }
}
