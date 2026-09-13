<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titel',
                'attr' => ['class' => 'form-control', 'placeholder' => 'T.ex. Sagan om ringen'],
            ])
            ->add('author', TextType::class, [
                'label' => 'Författare',
                'attr' => ['class' => 'form-control', 'placeholder' => 'T.ex. J.R.R. Tolkien'],
            ])
            ->add('isbn', TextType::class, [
                'label' => 'ISBN',
                'attr' => ['class' => 'form-control', 'placeholder' => 'T.ex. 9780261103252'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Beskrivning',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 4],
            ])
            ->add('save', SubmitType::class, [
                'label' => $options['is_edit'] ? 'Spara ändringar' : 'Lägg till bok',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
            'is_edit' => false,
        ]);
    }
}
