<?php

namespace App\Form;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la category'
            ])
            // ->add('slug')
            ->add('parent', EntityType::class, [
                'label' => "Category mère d'afiliation ( Facultatif )",
                'class' => Category::class,
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'query_builder' => function(CategoryRepository $cr)
                {
                    return $cr->createQueryBuilder('c')
                    ->where('c.parent IS NULL')
                    ->orderBy('c.name', 'ASC');
                },
            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
