<?php

namespace App\Form;

use App\Entity\Livre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre du livre'
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug (URL)'
            ])
            ->add('image', TextType::class, [
                'label' => 'URL de l\'image'
            ])
            ->add('resume', TextareaType::class, [
                'label' => 'Résumé'
            ])
            ->add('editeur', TextType::class, [
                'label' => 'Éditeur'
            ])
            ->add('dateEdition', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'édition'
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Quantite'
            ])
            ->add('isbn', TextType::class, [
                'label' => 'ISBN'
            ])
            ->add('date_dispo', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Date d\'indisponibilité (facultative)'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
