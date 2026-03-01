<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Formulari de registre d'usuaris.
 * Camps: nom, email, contrasenya i acceptació de termes.
 * La contrasenya no es mapeja directament a l'entitat (es hasheja al controlador).
 */
class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'El teu nom',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(message: 'Si us plau, introdueix el teu nom.'),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'email@exemple.com',
                    'class' => 'form-control',
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Accepto els termes i condicions',
                'mapped' => false,
                'constraints' => [
                    new IsTrue(message: 'Has d\'acceptar els termes i condicions.'),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Contrasenya',
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'Mínim 6 caràcters',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(message: 'Si us plau, introdueix una contrasenya.'),
                    new Length(
                        min: 6,
                        minMessage: 'La contrasenya ha de tenir com a mínim {{ limit }} caràcters.',
                        max: 4096,
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}