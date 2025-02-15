<?php

namespace App\Form;

use App\Entity\Devoir;
use App\Entity\Cours;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DevoirType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreD', TextType::class, [
                'label' => 'Titre du devoir',
            ])
            ->add('descrD', TextType::class, [
                'label' => 'Description',
            ])
            ->add('dateD', DateTimeType::class, [
                'label' => 'Date de remise',
                'widget' => 'single_text',
            ])
            ->add('comment', TextType::class, [
                'label' => 'Commentaire',
                'required' => false,
            ]);

        // Ajouter le champ 'cours' seulement si aucun cours n'est déjà défini
        if (!$builder->getData() || !$builder->getData()->getCours()) {
            $builder->add('cours', EntityType::class, [
                'class' => Cours::class,
                'choice_label' => 'titre',
                'label' => 'Cours associé',
                'placeholder' => 'Choisissez un cours',
                'required' => true,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devoir::class,
        ]);
    }
}