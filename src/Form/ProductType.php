<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulari per crear i editar productes.
 * Camps: títol, descripció, preu i imatge (URL opcional).
 * Les validacions estan definides a l'entitat Product amb atributs Assert.
 */
class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Títol',
                'attr' => [
                    'placeholder' => 'Introdueix el títol del producte',
                    'class' => 'form-control',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripció',
                'attr' => [
                    'placeholder' => 'Descriu el teu producte amb detall (mínim 10 caràcters)',
                    'rows' => 5,
                    'class' => 'form-control',
                ],
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Preu',
                'currency' => 'EUR',
                'attr' => [
                    'placeholder' => '0.00',
                    'class' => 'form-control',
                ],
            ])
            ->add('image', UrlType::class, [
                'label' => 'URL de la Imatge',
                'required' => false,
                'attr' => [
                    'placeholder' => 'https://exemple.com/imatge.jpg (opcional)',
                    'class' => 'form-control',
                ],
                'help' => 'Si no proporciones una imatge, se\'n generarà una automàticament.',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
