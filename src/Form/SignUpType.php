<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\IsTrue;

class SignUpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mail', EmailType::class,
                array('label' =>'Adresse email',))
            ->add('username', TextType::class,
                array('label' =>'Pseudo'))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Repetez le mot de passe'),))
            ->add('acceptTerms', CheckboxType::class, array(
                'label' => 'Acceptez vous les conditions d\'utilisation ?' ,
                'mapped' => false,
                'constraints' => new IsTrue(
                    array('message' =>'Vous devez accepter les conditions d\'utilisation')),));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'validation_groups' =>  array('signUp', 'Default'),
        ));
    }
}