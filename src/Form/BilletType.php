<?php

namespace App\Form;

use App\Entity\Billet;
use App\Entity\Event;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BilletType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Nom du billet'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du billets'
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix du billet',
                'scale' => 2
            ])
            ->add('featuredImage', FileType::class, [
                'label' => 'Image',
                'multiple' => false,
                'mapped' => false,
                'required' => false
            ])
            ->add('event', EntityType::class, [
                'label' => 'Evenement',
                'class' => Event::class,
                'required' => false
            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Billet::class,
        ]);
    }
}
