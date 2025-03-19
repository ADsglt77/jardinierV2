<?php

namespace App\Form;

use App\Entity\Devis;
use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class DevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Date will be auto-set, disable manual editing
            ->add('date', null, [
                'widget' => 'single_text',
                'disabled' => true,
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'libelle',
                'label' => 'Type de Haie',
                'mapped' => false,
            ])
            ->add('hauteur', NumberType::class, [
                'label' => 'Hauteur',
                'mapped' => false,
            ])
            ->add('largeur', NumberType::class, [
                'label' => 'Largeur',
                'mapped' => false,
            ])
            // You may decide not to render 'prixHaie' and 'haieType' as they can be calculated.
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
