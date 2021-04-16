<?php

namespace App\Form;

use App\Entity\Canyon;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CanyonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('shortDescription', TextareaType::class, ['label' => 'Résumé'])
            ->add('numberOfPlaces', TextType::class, ['label' => 'Nombre de places'])
            ->add('level', TextType::class, ['label' => 'Niveau'])
            ->add('ageNeeded', IntegerType::class, ['label' => 'Age requis'])
            ->add('location', TextType::class, ['label' => 'Lieu'])
            ->add('meetingPoint', TextType::class, ['label' => 'Point de rendez-vous'])
            ->add('gps', TextType::class, [
                'label' => 'Localisation GPS',
                'required' => false,
            ])
            ->add('abseiling', TextType::class, ['label' => 'Rappel'])
            ->add('knowledge', TextType::class, ['label' => 'Pré-requis'])
            ->add('duration', TextType::class, ['label' => 'Durée'])
            ->add('pictures', FileType::class, [
                // Donne le nom Image et non pictures
                 'label' => 'Images',
                // Champ multiple car plusieurs images
                'multiple' => true,
                // Non relié à la bdd
                'mapped' => false,
                'required' => false
                ])
            ->add('updatedAt', DateTimeType::class, [
                'label' => 'Modifié le',
                // génère un calendrier pour le choix de la date en "jj/mm/aaaa"
                'date_widget' => 'single_text'
            ]);
    }

    // Précise à symfony sur quelle classe repose la structure de ce formulaire
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Canyon::class,
        ]);
    }
}
