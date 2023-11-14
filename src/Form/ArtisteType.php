<?php

namespace App\Form;

use App\Entity\NationSound\Artiste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtisteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'artiste'
            ])
            ->add('featuredImage', FileType::class, [
                'label' => false,
                'mapped' => false,
                'required' =>false
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de l\'artiste'
            ])
            ->add('musicLink', TextType::class, [
                'label' => 'Lien vers un enregistrement ( exemple : YouTube )',
                'required'=> false,
            ])
            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artiste::class,

        ]);
    }
}
