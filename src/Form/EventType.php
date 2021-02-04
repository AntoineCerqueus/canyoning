<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Canyon;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startAt')
            ->add('endAt')
            // ->add('specificGuide')
            // ->add('users')
            ->add('canyon', EntityType::class, [ // Ajoute une champ sélecteur nommé Canyon de type Entité
                'class' => Canyon::class, // Spécifie que l'entité est celle de Canyon
                'choice_label' => 'name' // Affiche l'attribut nom de l'objet Canyon
            ])
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
