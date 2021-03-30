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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // https://symfony.com/doc/current/form/without_class.html
        $builder
            ->add('lastName', TextType::class, [
                'label' => 'Votre nom',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 2,
                        'max' => 40,
                    ]),
                ],
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Votre prénom',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 2,
                        'max' => 40,
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre adresse mail',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 7,
                        'max' => 50,
                    ]),
                ],
            ])
            ->add('phone', IntegerType::class, [
                'attr' => [
                    'oninvalid' => "setCustomValidity('Votre numéro de téléphone doit contenir 10 chiffres')"
                ],
                'label' => 'Votre téléphone',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 10,
                        'max' => 10,
                    ]),
                ],
            ])
            ->add('canyon',  ChoiceType::class, [
                'label' => 'Choix du canyon',
                'choices' => [ // Clé affichée pour le formulaire, valeure envoyée dans le mail
                    'Malvaux (matin)' => 'Malvaux (matin)',
                    'Malvaux (après-midi)' => 'Malvaux (après-midi)',
                    'Coiserette (matin) ' => 'Coiserette (matin)',
                    'Coiserette (après-midi) ' => 'Coiserette (après-midi)',
                    'Langouette (matin)' => 'Langouette (matin)',
                    'Langouette (après-midi)' => 'Langouette (après-midi)',
                    'Grosdar (matin)' => 'Grosdar (matin)',
                    'Grosdar (après-midi)' => 'Grosdar (après-midi)',
                    'Grosdar Intégral' => 'Grosdar Intégral'
                ],

            ])
            ->add('when', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Quand ?',
                'constraints' => [
                    new NotBlank(),
                ],

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
                    'class' => "nav-button"
                ],
                'label' => 'envoyer'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                // https://www.strangebuzz.com/en/blog/disable-the-html5-validation-of-all-your-symfony-forms-with-a-feature-flag
                'novalidate' => 'novalidate', // comment me to reactivate the html5 validation! 
            ]
        ]);
    }
}
