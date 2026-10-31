<?php

namespace App\Form;

use App\Entity\Gadget;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GadgetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Namn',
                'attr' => ['placeholder' => 'T.ex. UltraPod Pro'],
            ])
            ->add('brand', TextType::class, [
                'label' => 'Varumärke',
                'attr' => ['placeholder' => 'T.ex. Sonix'],
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Kategori',
                'choices' => [
                    'Mobil' => 'Mobil',
                    'Dator' => 'Dator',
                    'Ljud' => 'Ljud',
                    'Gaming' => 'Gaming',
                    'Tillbehör' => 'Tillbehör',
                    'Smart hem' => 'Smart hem',
                    'Kamera' => 'Kamera',
                    'Wearable' => 'Wearable',
                ],
            ])
            ->add('releaseYear', IntegerType::class, [
                'label' => 'Lanseringsår',
                'attr' => ['min' => 1970, 'max' => 2100],
            ])
            ->add('price', NumberType::class, [
                'label' => 'Pris (kr)',
                'html5' => true,
                'attr' => ['min' => 0, 'step' => '1'],
            ])
            ->add('rating', ChoiceType::class, [
                'label' => 'Betyg',
                'choices' => [
                    '★☆☆☆☆ (1)' => 1,
                    '★★☆☆☆ (2)' => 2,
                    '★★★☆☆ (3)' => 3,
                    '★★★★☆ (4)' => 4,
                    '★★★★★ (5)' => 5,
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Beskrivning',
                'required' => false,
                'attr' => ['rows' => 4, 'placeholder' => 'Kort beskrivning av prylen...'],
            ])
            ->add('save', SubmitType::class, [
                'label' => $options['is_edit'] ? 'Spara ändringar' : 'Lägg till pryl',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gadget::class,
            'is_edit' => false,
        ]);
    }
}
