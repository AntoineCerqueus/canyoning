<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title', ChoiceType::class, [
            'label' => 'Disponibilité',
            'choices' => [
                'Disponible' => 'Disponible',
                'Complet' => 'Complet'
            ]
        ])
            ->add('backgroundColor', ColorType::class, [
                'label' => 'Couleur de fond'
            ])
            ->add('borderColor', ColorType::class, [
                'label' => 'Couleur de bordure'
            ])
            ->add('textColor', ColorType::class, [
                'label' => 'Couleur du texte'
            ])
            ->add('startAt', DateTimeType::class, [
                'label' => 'Début',
                'date_widget' => 'single_text'
            ])
            ->add('endAt', DateTimeType::class, [
                'label' => 'Fin',
                'date_widget' => 'single_text'
            ])
            // Précise de travailler avec l'attibut guide de la classe User
            ->add('guide', EntityType::class, [ // Travaille sur l'attribut 'guide' d'une entité
                'class' => User::class, // Spécifie quelle entité
                // Construit un requête pour trouver les users qui sont guide avec la valeur égale à 'true'
                'query_builder' => function (UserRepository $userRepository) {
                    return $userRepository->createQueryBuilder('u')
                        ->where('u.guide = true');
                },
                // Donne le nom 'Guide' au label de l'input
                'label' => 'Guide',
                // Ajoute un champ sélecteur pour choisir le guide associé au canyon affiché par prénom
                'choice_label' => 'firstName'
            ])
            ->add('availableSlots', ChoiceType::class, [
                'label' => 'Places restantes',
                'choices' => [
                    '10' => '10',
                    '9' => '9',
                    '8' => '8',
                    '7' => '7',
                    '6' => '6',
                    '5' => '5',
                    '4' => '4',
                    '3' => '3',
                    '2' => '2',
                    '1' => '1',
                    '0' => '0'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
