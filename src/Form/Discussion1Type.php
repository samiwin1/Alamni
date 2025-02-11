<?php

namespace App\Form;

use App\Entity\Discussion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints as Assert;

class Discussion1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('_token', HiddenType::class, [
            'mapped' => false,
        ])
            ->add('adresse', EmailType::class, [
                'label' => 'Adresse Email',
                'attr' => ['placeholder' => 'Entrez votre email', 'class' => 'form-control'],
                'required' => true,
            ])
            ->add('sujet', TextType::class, [
                'label' => 'Sujet',
                'attr' => ['placeholder' => 'Votre sujet?', 'class' => 'form-control'],
                'required' => true,
            ])
            ->add('destinataire', TextType::class, [
                'label' => 'Destinataire',
                'attr' => ['placeholder' => 'À qui ?', 'class' => 'form-control'],
                'required' => true,
            ])
            ->add('contenu', TextareaType::class, [
                'label' => 'Contenu',
                'attr' => ['placeholder' => 'Contenu du message', 'class' => 'form-control'],
                'required' => true,
            ])
            
            // Inside buildForm() method
            ->add('role', ChoiceType::class, [
                'label' => 'Rôle',
                'choices' => [
                    'Étudiant' => 'etudiant',
                    'Admin' => 'admin',
                    'Enseignant' => 'enseignant',
                    'Parent' => 'parent',
                ],
                'placeholder' => 'Sélectionnez votre rôle', // Optional default selection
                'attr' => ['class' => 'form-select'], // Bootstrap styling
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le rôle est obligatoire.']),
                    new Assert\Choice([
                        'choices' => ['etudiant', 'admin', 'enseignant', 'parent'],
                        'message' => 'Veuillez sélectionner un rôle valide.',
                    ]),
                ],
            ])
          

            ->add('professeur', TextType::class, [
                'label' => 'Professeur',
                'attr' => ['placeholder' => 'Nom du professeur', 'class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom du professeur est obligatoire.']),
                    new Assert\Length([
                        'min' => 3,
                        'max' => 255,
                        'minMessage' => 'Le nom du professeur doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom du professeur ne peut pas dépasser {{ limit }} caractères.'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[\p{L}\s-]+$/u',
                        'message' => 'Le nom du professeur ne peut contenir que des lettres, des espaces et des tirets.'
                    ]),
                ],
            ])
            
            
            ->add('eleve', IntegerType::class, [
                'label' => 'ID Élève',
                'required' => false, 
                'attr' => ['class' => 'form-control', 'placeholder' => 'Entrez l\'ID de l\'élève'],
                'constraints' => [
                    new Assert\Positive(['message' => 'L\'ID de l\'élève doit être un nombre positif.']),
                ],
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Discussion::class,
        ]);
    }
}

