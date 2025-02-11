<?php

namespace App\Form;

use App\Entity\Planning;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PlanningType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Course Name',
                'attr' => ['placeholder' => 'Enter the course name'],
            ])
            ->add('science', TextType::class, [
                'label' => 'Science',
                'attr' => ['placeholder' => 'Enter the science field'],
            ])
            ->add('startTime', DateTimeType::class, [
                'label' => 'Start Time',
                'widget' => 'single_text',
                'attr' => ['class' => 'datetime-picker'],
            ])
            ->add('endTime', DateTimeType::class, [
                'label' => 'End Time',
                'widget' => 'single_text',
                'attr' => ['class' => 'datetime-picker'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Planning::class,
        ]);
    }
}