<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Canyon;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startAt')
            ->add('endAt')
            // Ajoute un champ sélecteur pour choisir le guide associé au canyon
            ->add('guide', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (UserRepository $userRepository) {
                    return $userRepository->createQueryBuilder('u')
                    ->where('u.guide = true');
                    // ->orderBy('u.firstName, ASC');
                },
                'label' => 'Guide',
                'choice_label' => 'firstName'
            ])
            // ->add('canyon', EntityType::class, [ // Travail sur un objet de l'entité Canyon
            //     'class' => Canyon::class, // Spécifie que l'entité est celle de Canyon
            //     'choice_label' => 'name' // Affiche les attributs nom dans un sélecteur
            // ])
            // ->add('guide')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
