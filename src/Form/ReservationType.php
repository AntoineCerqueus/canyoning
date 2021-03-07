<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' => 'Votre nom'
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Votre prénom'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre adresse mail'
            ])
            ->add('phone', IntegerType::class, [
                'label' => 'Votre téléphone',
                'invalid_message' => 'Le numéro de téléphone doit être composé de %num% chiffres',
                'invalid_message_parameters' => ['%num%' => 10],
            ])
            ->add('canyon',  ChoiceType::class, [
                'label' => 'Choix du canyon',
                'choices' => [
                    'Malvaux' => 'Malvaux',
                    'Coiserette' => 'Coiserette',
                    'Langouette' => 'Langouette',
                    'Grosdar' => 'Grosdar',
                    'Grosdar Intégral' => 'Grosdar Intégral'
                ],

            ])
            ->add('when', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Quand ?'

            ])
            ->add('morning_or_noon', ChoiceType::class, [
                'label' => false,
                'expanded' => true, 
                'multiple' => false,
                'choices' => [
                    'Matin' => 'Matin',
                    'Après-midi' => 'Après-midi'
                ]
            ])
            ->add('extra_personn', ChoiceType::class, [
                'label' => 'Des personnes vous accompagnent ?',
                'expanded' => true, 
                'multiple' => false,
                'required' => false,
                'placeholder' => 'Aucune', // Change le texte "None" en aucune
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9'
                ]
            ])
            ->add('message', TextareaType::class, [
                'attr' => [
                    'placeholder' => "C'est ici...",
                    'rows' => 6
                ],
                'required' => false,
                'label' => 'Envie de nous transmettre une autre information ?'
            ])
            ->add('send', SubmitType::class, [
                'attr' => [
                    'class' => "nav"
                ],
                'label' => 'envoyer'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
