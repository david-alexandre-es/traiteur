<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Theme;
use App\Entity\Regime;
use App\Entity\Plat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, ['label' => 'Titre du menu'])
            ->add('description', TextareaType::class, ['label' => 'Description', 'required' => false])
            ->add('prix_par_personne', MoneyType::class, ['label' => 'Prix par personne', 'currency' => 'EUR'])
            ->add('nombre_personne_minimum', IntegerType::class, ['label' => 'Nombre de personnes minimum'])
            ->add('quantite_restante', IntegerType::class, ['label' => 'Quantité restante', 'required' => false])
            ->add('theme', EntityType::class, [
                'class' => Theme::class,
                'choice_label' => 'libelle',
                'label' => 'Thème',
            ])
            ->add('regimes', EntityType::class, [
                'class' => Regime::class,
                'choice_label' => 'libelle',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Régimes',
            ])
            ->add('plats', EntityType::class, [
                'class' => Plat::class,
                'choice_label' => 'titrePlat',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Plats',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}