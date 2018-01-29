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

use App\Form\UserPictureType;


use App\Entity\Picture;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mail', EmailType::class,array(
                'label'=>'Adresse email',
                'required'    => false,
            ))
            ->add('name', TextType::class,array(
                'label'=>'Nom',
                'required'    => false,
            ))
            ->add('surname',TextType::class,array(
                'label'=>'Nom de famille',
                'required'    => false,
            ))
            ->add('currentPassword',PasswordType::class,array(
                'required'    => false,
            ))
            ->add('resetPassword',ResetPasswordType::class,array(
                'required'    => false,
            ))
            ->add('picture',UserPictureType::class,array(
                'required'    => false,
            )); 
    }

    public function configureOptions(OptionsResolver $resolver)
    {
         
 
    }
}