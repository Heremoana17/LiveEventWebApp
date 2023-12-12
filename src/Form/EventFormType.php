<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Event;
use App\Entity\City;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('introduction')
            ->add('content')
            ->add('date', DateType::class, [
                'widget' => 'choice',
                'input'  => 'datetime_immutable'
            ])
            // ->add('slug')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'expanded' => false,
                'multiple' => true,
                'group_by' => 'parent.name',
                'query_builder' => function(CategoryRepository $cr)
                {
                    return $cr->createQueryBuilder('c')
                    ->where('c.parent IS NOT NULL')
                    ->orderBy('c.name', 'ASC');
                },
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'placeholder' => ' ',
                'expanded' => false,
                'multiple' => false,
                'required' => true
            ])
            ->add('featuredImage', FileType::class, [
                'label' => false,
                'multiple' => false,
                'mapped' => false,
                'required' => false
            ])
            ->add('images', FileType::class,[
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            ->add('video', TextType::class,[
                'label' => false,
                'required' => false
            ])
            //champ pour rediger le mail pour les abonnées newsletter
            ->add('valider', CheckboxType::class, [
                'label' => 'Voulez vous informer les abonnées de ce nouvel evenemant ?',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'onclick' => 'myFunction()'
                ]
            ])
            ->add('subject', TextType::class, [
                'label' => 'Titre',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'hero',
                    'disabled' => 'disabled'
                ]
            ])
            ->add('Content', TextType::class, [
                'label' => 'Contenu',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'hero',
                    'disabled' => 'disabled'
                ]
            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
