<?php

namespace App\Form;

use App\Entity\Discount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class DiscountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $year_start = new \DateTime();
        $year_end = new \DateTime('+5 YEAR');
        
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('amount', IntegerType::class, [
                'label' => 'Montant (en %)',
                'invalid_message' => 'Le montant doit être un nombre'
            ])
            ->add('begin_at', DateType::class, [
                'label' => 'Commence le',
                'format' => 'yyyy-MM-dd',
                'years' => range($year_start->format('Y'), $year_end->format('Y')),
                'placeholder' => [
                    'year' => 'Année',
                    'month' => 'Mois',
                    'day' => 'Jour'
                ],
                'invalid_message' => 'Veuillez entrer une date valide'
            ])
            ->add('end_at', DateType::class, [
                'label' => 'Fini le',
                'format' => 'yyyy-MM-dd',
                'years' => range($year_start->format('Y'), $year_end->format('Y')),
                'placeholder' => [
                    'year' => 'Année',
                    'month' => 'Mois',
                    'day' => 'Jour'
                ],
                'invalid_message' => 'Veuillez entrer une date valide'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Discount::class,
        ]);
    }
}
