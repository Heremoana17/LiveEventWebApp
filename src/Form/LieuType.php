<?php

namespace App\Form;

use App\Entity\NationSound\Lieu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Nom du lieu'
            ])
            ->add('category', ChoiceType::class,[
                'label' => 'Category',
                'placeholder' => '',               
                'choices'  => [
                    'Scene' => 'Scene',
                    'Toilette' => 'Toilette',
                    'Bars' => 'Bars',
                    'Poste de secours' => 'Poste de secours',
                    'Hotel' => 'Hotel',
                ],
            ])
            ->add('featuredImage', FileType::class, [
                'label' => false,
                'mapped' => false,
                'required' => false
            ])
            ->add('description', TextareaType::class,[
                'label' => 'Description',
                'required' => false
            ])
            ->add('GPSPtLat', NumberType::class,[
                'scale' => 5,
                'label' => 'Coordonnées GPS Latitude'
            ])
            ->add('GPSPtLng', NumberType::class,[
                'scale' => 5,
                'label' => 'Coordonnées GPS Longitude'
            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
