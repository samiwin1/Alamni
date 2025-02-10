<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Votre nom'
                ]
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Votre prénom'
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Votre email'
                ]
            ]);

        if ($options['is_edit']) {
            $builder->add('mot_de_passe', PasswordType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Laissez vide pour garder le même mot de passe'
                ]
            ]);
        } else {
            $builder->add('mot_de_passe', PasswordType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Minimum 8 caractères'
                ]
            ]);
        }

        if (!$options['is_edit']) {
            $builder->add('role', ChoiceType::class, [
                'choices' => [
                    'Élève' => 'ROLE_ELEVE',
                    'Enseignant' => 'ROLE_ENSEIGNANT',
                    'Parent' => 'ROLE_PARENT',
                    'Administrateur' => 'ROLE_ADMIN'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ]);
        }

        $builder
            ->add('niveau', ChoiceType::class, [
                'label' => 'Niveau d\'études',
                'required' => false,
                'choices' => [
                    'Collège' => 'college',
                    'Lycée' => 'lycee'
                ],
                'placeholder' => 'Sélectionnez un niveau',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('nom_niveau', TextType::class, [
                'label' => 'Nom du niveau',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex: 6ème, 5ème, 2nde, etc.'
                ]
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo de profil',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG, GIF)',
                    ])
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false,
        ]);
    }
}