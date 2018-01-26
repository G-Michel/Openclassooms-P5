<?php

namespace App\Form;

use App\Entity\Picture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserPictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('file', FileType::class, [
            'label'       => 'Photo',
            'attr'        => ['class'=>'form-control-file'],
            'required'    => false,
            'mapped'      => true,
            'constraints' => [
                new Image([
                    'minWidth'  => 50,
                    'maxWidth'  => 2000,
                    'minHeight' => 50,
                    'maxHeight' => 2000,
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
        ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => Picture::class,
        ]);
    }
}
