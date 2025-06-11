<?php
// src/Form/TransactionType.php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Transaction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => ['class' => 'form-control']
            ])
            ->add('amount', MoneyType::class, [
                'label' => 'Montant',
                'currency' => 'EUR',
                'attr' => ['class' => 'form-control']
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    'Revenu' => 'income',
                    'Dépense' => 'expense'
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Sélectionnez une catégorie',
                'required' => false
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 3]
            ])
            ->add('date', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
