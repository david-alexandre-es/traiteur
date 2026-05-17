<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnulationCommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mode_contact', ChoiceType::class, [
                'label' => 'Mode de contact avec le client',
                'choices' => [
                    'Appel GSM' => 'gsm',
                    'Mail' => 'mail',
                ],
            ])
            ->add('motif_annulation', TextareaType::class, [
                'label' => 'Motif d\'annulation',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}