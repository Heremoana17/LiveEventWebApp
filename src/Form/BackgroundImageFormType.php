<?php

namespace App\Form;

use App\Entity\BackgroundImage;
use App\Entity\Page;
use PhpParser\Parser\Multiple;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BackgroundImageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', FileType::class, [
                'label' => 'Image',
                'required' => true,
                'multiple' => false,
                'mapped' =>false,
            ])
            ->add('page', EntityType::class, [
                'class' => Page::class,
                'label' => 'A quelle page voulez vous associé cette image ?',
                'required' => true,
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BackgroundImage::class,
        ]);
    }
}
