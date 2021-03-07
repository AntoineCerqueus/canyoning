<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class, [
                'attr' => [
                    'placeholder' => "Votre nom"
                ],
                'label' => false
            ])
            ->add('firstName', TextType::class, [
                'attr' => [
                    'placeholder' => "Votre prénom"
                ],
                'label' => false
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => "Votre e-mail"
                ],
                'label' => false
            ])
            ->add('message', TextareaType::class,[
                'attr' => [
                    'placeholder' => "Votre message",
                    'rows' => 10
                ],
                'label' => false
            ])
            ->add('envoyer', SubmitType::class, [
                'attr' => [
                    'class' => "nav"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
