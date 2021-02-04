<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Entity\Canyon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CanyonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('numberOfPlaces')
            ->add('level')
            ->add('ageNeeded')
            ->add('halfDay')
            ->add('fullDay')
            ->add('location')
            ->add('meetingPoint')
            ->add('gps')
            ->add('abseiling')
            ->add('knowledge')
            ->add('pictures', CollectionType::class, [
                'entry_type' => PictureCanyonType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Canyon::class,
        ]);
    }
}
