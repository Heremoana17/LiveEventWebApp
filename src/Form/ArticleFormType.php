<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Event;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'article'
            ])
            ->add('introduction', TextareaType::class, [
                'label' => 'Introduction'
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
            ])
            // ->add('slug', TextType::class, [
            //     'label' => 'Slug de pour l\'URL'
            // ])
            ->add('categories', EntityType::class,[
                'class' => Category::class,
                'placeholder' =>'',
                'group_by' => 'parent.name',
                'query_builder' => function(CategoryRepository $cr)
                {
                    return $cr->createQueryBuilder('c')
                    ->where('c.parent IS NOT NULL')
                    ->orderBy('c.name', 'ASC');
                }
            ])
            ->add('featuredImage', FileType::class, [
                'label' => false,
                'mapped' => false,
                'multiple' => false,
                'required' => false
            ])
            ->add('images', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            ->add('video', TextType::class, [
                'label' => false,
                'required' => false
            ])
            // champ pour l'evenement référent
            ->add('relatedEvent', EntityType::class, [
                'class' => Event::class,
                'label' => 'Votre article fait-il référence à un évènement en particulier ?',
                'required' => false
            ])
            //champ pour rediger le mail pour les abonnées newsletter
            ->add('Valider', CheckboxType::class, [
                'label' => 'Voulez vous informer les abonnées de cette nouvelle actualité ?',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'onclick' => "myFunction()"
                ]
            ])
            ->add('Subject', TextareaType::class, [
                'label' => 'Title',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class'=>'hero',
                    'disabled' => 'disabled'
                ]
            ])
            ->add('Content', TextareaType::class, [
                'label' => 'Contenut',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class'=>'hero',
                    'disabled' => 'disabled'
                ]
            ])

            //boutton d'envoie du formulurair
            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
