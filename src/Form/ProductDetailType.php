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
            ->add('stock', IntegerType::class)
            ->add('developer', TextType::class)
            ->add('publisher', TextType::class)
            ->add('soldNumber', IntegerType::class)
            ->add('releaseDate', DateType::class)
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
