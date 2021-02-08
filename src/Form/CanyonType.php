<?php

namespace App\Form;

use App\Entity\Canyon;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CanyonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('numberOfPlaces', TextType::class, ['label' => 'Nombre de places'])
            ->add('level', TextType::class, ['label' => 'Niveau'])
            ->add('ageNeeded', TextType::class, ['label' => 'Age requis'])
            ->add('halfDay', CheckboxType::class, [
                'label' => 'Demi-journée',
                'required' => false,
            ])
            ->add('fullDay', CheckboxType::class, [
                'label' => 'Journée',
                'required' => false,
            ])
            ->add('location', TextType::class, ['label' => 'Lieu'])
            ->add('meetingPoint', TextType::class, ['label' => 'Point de rendez-vous'])
            ->add('gps', TextType::class, [
                'label' => 'Localisation GPS',
                'required' => false,
            ])
            ->add('abseiling', TextType::class, ['label' => 'Rappel'])
            ->add('knowledge', TextType::class, ['label' => 'Pré-requis'])
            ->add(
                'pictures',
                CollectionType::class,
                [
                    'entry_type' => PictureCanyonType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'label' => 'Images'
                ],
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Canyon::class,
        ]);
    }
}
