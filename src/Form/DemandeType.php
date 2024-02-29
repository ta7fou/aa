<?php

namespace App\Form;

use App\Entity\Demande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DemandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('animalId', TextType::class, [
            'disabled' => true, // This makes the field non-modifiable
            'label' => 'Animal ID', // Optional: you can customize the label
        ])
        ->add('Sujet', TextType::class, [
            'label' => 'Sujet', // Customize the label as needed
        ])
        ->add('details', TextareaType::class, [
            'label' => 'Details', // Customize the label as needed
        ])
        ->add('iduser', TextType::class, [
            'label' => 'User ID', // Customize the label as needed
            'disabled' => $options['is_edit']
        ]);
    }

    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
            'is_edit' => false, // Default to false, indicating it's not for editing
        ]);
    }
}
