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
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Disponibilité'
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
