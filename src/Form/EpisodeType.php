<?php

namespace App\Form;

use App\Entity\NationSound\Artiste;
use App\Entity\NationSound\Day;
use App\Entity\NationSound\Episode;
use App\Entity\NationSound\Scene;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EpisodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('hour', TimeType::class, [
                'label' => 'Heure'
            ])
            ->add('artiste', EntityType::class, [
                'class' => Artiste::class,
                'placeholder' => ' ',
                'label' => 'Artiste',
                'required' => false
            ])
            ->add('scene', EntityType::class, [
                'class' => Scene::class,
                'label' => 'Scene',
                'placeholder' => ' ',
                'required' => false
            ])
            ->add('day', EntityType::class, [
                'class' => Day::class,
                'required' => false,
                'label' => 'Journée de l\'épisode',
                'placeholder' => ' ',
            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Episode::class,
        ]);
    }
}
