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
            ->add('file', FileType::class, [
                'label'       => 'Photo',
                'attr'        => ['class'=>'form-control-file'],
                'required'    => false,
                'mapped'      => true,
                'constraints' => [
                    new Image([
                        'minWidth'  => 30,
                        'maxWidth'  => 500,
                        'minHeight' => 30,
                        'maxHeight' => 500,
                        'mimeTypesMessage' => "Le fichier n’est pas une image valide",
                        'minWidthMessage'  => "La largeur de l'image est trop petite ({{ width }} px), La largeur minimum autorisée est {{ min_width }} px",
                        'maxWidthMessage'  => "La largeur de l'image est trop grande ({{ width }} px), La largeur maximum autorisée est {{ max_width }} px",
                        'minHeightMessage' => "La hauteur de l'image est trop petite ({{ width }} px), La hauteur minimum autorisée est {{ min_width }} px",
                        'maxHeightMessage' => "La hauteur de l'image est trop grande ({{ width }} px), La hauteur maximum autorisée est {{ max_width }} px"
                    ]),
                ]
            ])
            ->add('alt', TextType::class, [
                'label'       => 'Description de la photo',
                'required'    => false,
            ])
            ->add('currentPassword',PasswordType::class,array(
                'required'    => false,
            ))
            ->add('resetPassword',ResetPasswordType::class,array(
                'required'    => false,

            ));

                
    }

    public function configureOptions(OptionsResolver $resolver)
    {
 
    }
}