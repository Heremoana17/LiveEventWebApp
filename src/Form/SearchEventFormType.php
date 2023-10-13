<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\City;
use App\Entity\Event;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchEventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('genre', EntityType::class, [
                'label' => false,
                // 'label' => 'Catégory',
                'class' => Category::class,
                'placeholder' => 'Category',
                'required' => false,
                'mapped' => false,
                'expanded' => false,
                'multiple' => false,
                'group_by' => 'parent.name',
                'query_builder' => function(CategoryRepository $cr)
                {
                    return $cr->createQueryBuilder('c')
                    ->where('c.parent IS NOT NULL')
                    ->orderBy('c.name', 'ASC');
                },
            ])

            ->add('name', EntityType::class, [
                'label' => false,
                // 'label' => 'Nom de l\'évènement',
                'class' => Event::class,
                'placeholder' => 'Nom de l\'évènement',
                'required' => false,
                'mapped' => false,
                'expanded' => false,
                'multiple' => false,
            ])

            ->add('city', EntityType::class, [
                'label' => false,
                // 'label' => 'Ville',
                'class' => City::class,
                'placeholder' => 'Ville',
                'required' => false,
                'mapped' => false,
                'expanded' => false,
                'multiple' => false,
            ])
            
            ->add('date', DateType::class, [
                'label' => false,
                // 'label' => 'Date',
                'widget' => 'choice',
                'input'  => 'datetime_immutable',
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
                'required' => false,
            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
