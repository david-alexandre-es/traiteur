<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Menu;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!$options['menu_disabled']) {
            $builder->add('menu', EntityType::class, [
                'class' => Menu::class,
                'choice_label' => 'titre',
                'label' => 'Menu choisi',
            ]);
        }

        $builder
            ->add('date_prestation', DateType::class, [
                'label' => 'Date de la prestation',
                'widget' => 'single_text',
            ])
            ->add('heure_livraison', TimeType::class, [
                'label' => 'Heure de livraison',
                'widget' => 'single_text',
                'required' => false,
                'input' => 'string',
            ])
            ->add('adresse_prestation', TextType::class, [
                'label' => 'Adresse de la prestation',
            ])
            ->add('ville_prestation', TextType::class, [
                'label' => 'Ville de la prestation',
            ])
            ->add('nombre_personne', IntegerType::class, [
                'label' => 'Nombre de personnes',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
            'menu_disabled' => false,
        ]);
    }
}