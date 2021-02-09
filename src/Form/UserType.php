<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, [
                'attr' => [
                    'placeholder' => "Votre e-mail"
                ]
            ])
            // ->add('roles')
            ->add('password', TextType::class, [
                'attr' => [
                    'placeholder' => "Votre mot de passe"
                ]
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'placeholder' => "Votre nom de famille"
                ]
            ])
            ->add('firstName', TextType::class, [
                'attr' => [
                    'placeholder' => "Votre prénom"
                ]
            ])
            ->add('size', IntegerType::class, [
                'attr' => [
                    'placeholder' => "Votre taille"
                ]
            ])
            ->add('weight', IntegerType::class, [
                'attr' => [
                    'placeholder' => "Votre poids"
                ]
            ])
            ->add('phone', TextType::class, [
                'attr' => [
                    'placeholder' => "Votre téléphone"
                ]
            ])
            ->add('status')
            // ->add('isVerified')
            // ->add('event')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
