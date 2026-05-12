<?php

namespace App\Form;

use App\Entity\Plat;
use App\Entity\Allergene;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre_plat', TextType::class, ['label' => 'Titre du plat'])
            ->add('note', TextType::class, ['label' => 'Note', 'required' => false])
            ->add('allergenes', EntityType::class, [
                'class' => Allergene::class,
                'choice_label' => 'libelle',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Allergènes',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plat::class,
        ]);
    }
}