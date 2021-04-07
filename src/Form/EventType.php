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

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('backgroundColor', ColorType::class)
            ->add('borderColor', ColorType::class)
            ->add('textColor', ColorType::class)
            ->add('startAt', DateTimeType::class, [
                'date_widget' => 'single_text'
            ])
            ->add('endAt', DateTimeType::class, [
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
            // ->add('canyon', EntityType::class, [ // Travaille sur un objet de l'entité Canyon
            //     'class' => Canyon::class, // Spécifie que l'entité est celle de Canyon
            //     'choice_label' => 'name' // Affiche les attributs nom dans un sélecteur
            // ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
