<?php
namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user_email', EmailType::class, [
                'label' => 'Votre Email',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('admin_mail', EmailType::class, [
                'label' => 'Email de l\'Admin',
                'attr' => ['class' => 'form-control'],
                'required' => true, // 🟢 Assurez-vous que ce champ est bien obligatoire
            ])
            
            
            ->add('role', ChoiceType::class, [
                'label' => 'Rôle',
                'choices' => [
                    'Parent' => 'parent',
                    'Enseignant' => 'enseignant',
                    'Étudiant' => 'etudiant',
                ],
                'placeholder' => 'Sélectionnez un rôle',
                'attr' => ['class' => 'form-select'],
                'required' => true,
                'empty_data' => 'parent', // Ensure default value
            ])
            
            
            ->add('objet', TextType::class, [
                'label' => 'Objet de la Réclamation',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'rows' => 4],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
