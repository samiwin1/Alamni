<?php

namespace App\Form;

use App\Entity\Cours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;


class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre du cours',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le titre du cours est obligatoire.',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control shadow-sm rounded-3',
                    'placeholder' => 'Saisissez le titre du cours'
                ]
            ])
            ->add('descrC', TextareaType::class, [
                'label' => 'Description',
                'constraints' => [
                    new NotBlank([
                        'message' => 'La description est obligatoire.',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control shadow-sm rounded-3',
                    'placeholder' => 'Saisissez la description'
                ]
            ])
            ->add('matiereC', TextType::class, [
                'label' => 'Matière',
                'constraints' => [
                    new NotBlank([
                        'message' => 'La matière est obligatoire.',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control shadow-sm rounded-3',
                    'placeholder' => 'Saisissez la matière'
                ]
            ])
            ->add('dateC', DateTimeType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => 'La date est obligatoire.',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control shadow-sm rounded-3'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
