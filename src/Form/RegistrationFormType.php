<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => false,
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir votre prénom'
                    ])
                ]
            ])
            ->add('lastName', TextType::class, [
                'required' => false,
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir votre nom'
                    ])
                ]
            ])
            ->add('address', TextType::class, [
                'required' => false,
                'label' => 'Adresse',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir votre adresse'
                    ])
                ]
            ])
            ->add('city', TextType::class, [
                'required' => false,
                'label' => 'Ville',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir votre ville'
                    ])
                ]
            ])
            ->add('zipcode', TextType::class, [
                'required' => false,
                'label' => 'Code postal',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir votre code postal'
                    ])
                ]
            ])
            ->add('phone', TextType::class, [
                'required' => false,
                'label' => 'Téléphone'
            ])
            ->add('email', TextType::class, [
                'required' => false,
                'label' => 'Email',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir votre adresse email'
                    ])
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => "J'accèpte les conditions générales",
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions générales.',
                    ])
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "Les mots de passe ne correspondent pas",
                'required' => false,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confimer votre mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir votre mot de passe'
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => "Votre mot de passe doit contenir minimum 8 caractères"
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()\-_=+{};:,<.>\/?]).{8,}$/',
                        'match' => true,
                        'message' => "Le mot de passe doit contenir au moins une minuscule, une majuscule, un chiffre et un caractère spécial (@$!#%?&)"
                    ])
                ]
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
