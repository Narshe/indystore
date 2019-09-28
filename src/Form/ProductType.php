<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom le peut pas être vide'
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('detail_id')
            ->add('price', NumberType::class, [
                'label' => 'Prix',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez entrer un prix'
                    ]),
                    new Type([
                        'type' => 'float', 
                        'message' => 'Le prix doit être de type float'
                    ])
               
                ]
            ])
            ->add('stock', IntegerType::class, [
                'label' => 'Quantité',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez entrer une quantité'
                    ]),
                    new Type([
                        'type' => 'int', 
                        'message' => 'Le prix doit être de type integer'
                    ])

                ]
            ])
            ->add('visible', CheckboxType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
