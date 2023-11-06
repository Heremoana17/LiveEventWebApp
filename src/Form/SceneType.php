<?php

namespace App\Form;

use App\Entity\NationSound\Scene;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SceneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la scene'
            ])
            ->add('featuredImage', FileType::class, [
                'label' => false,
                'required'=> false,
                'mapped' => false
            ])
            ->add('GPSPtLat', IntegerType::class,[
                'label' => 'Coordonnées GPS Latitude'
            ])
            ->add('GPSPtLng', IntegerType::class,[
                'label' => 'Coordonnées GPS Longitude'
            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Scene::class,
        ]);
    }
}
