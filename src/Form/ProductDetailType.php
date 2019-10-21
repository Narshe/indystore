<?php

namespace App\Form;

use App\Entity\ProductDetail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

use App\Entity\Discount;

class ProductDetailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('developer', TextType::class)
            ->add('publisher', TextType::class)
            ->add('stock', IntegerType::class, [
                'invalid_message' => 'Veuillez entrer un nombre',
                'attr' => [
                    'value' => 0
                ]
            ])
            ->add('soldNumber', IntegerType::class, [
                'invalid_message' => 'Veuillez entrer un nombre',
                'attr' => [
                    'value' => 0
                ]
            ])
            ->add('releaseDate', DateType::class, [
                'years' => range(1980, 2025),
                'format' => 'yyyy-MM-dd',
                'placeholder' => [
                    'year' => 'Année',
                    'month' => 'Mois',
                    'day' => 'Jour'
                ],
                'invalid_message' => 'Veuillez entrer une date valide'
            ])
            ->add('discount', EntityType::class, [
                'class' => Discount::class,
                'choice_label' => 'discount_title',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.amount', 'ASC')
                        ->where('d.end_at < :date')
                        ->setParameter('date', new \DateTime())
                    ;
                },
                'invalid_message' => 'Cette promotion n\'existe pas'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductDetail::class,
        ]);
    }
}
