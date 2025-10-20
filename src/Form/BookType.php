<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du livre',
                'attr' => ['placeholder' => 'Entrez le titre du livre']
            ])
            ->add('publicationDate', DateType::class, [
                'label' => 'Date de publication',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Science-Fiction' => 'Science-Fiction',
                    'Mystery' => 'Mystery',
                    'Autobiography' => 'Autobiography',
                ],
                'label' => 'Catégorie',
                'placeholder' => 'Sélectionnez une catégorie',
            ])
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'username', // ou 'name' si tu veux
                'placeholder' => 'Sélectionnez un auteur',
                'label' => 'Auteur'
            ])
->add('published', CheckboxType::class, [
    'label' => 'Disponible',
    'required' => false,
    'data' => true,
])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
