<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'input ',
                    'placeholder' => "Titre"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de saisir le titre de l'article"
                    ])
                ]
            ])
            ->add('reference', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'input ',
                    'placeholder' => "Référence"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de saisir la référence de l'article"
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'textarea',
                    'placeholder' => "Description",
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de saisir la déscription de l'article"
                    ])
                ]
            ])
            ->add('color', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'input ',
                    'placeholder' => "Couleur"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de saisir la couleur de l'article"
                    ])
                ]
            ])
            ->add('size', ChoiceType::class, [
                'required' => false,
                'label' => false,
                'placeholder' => 'Sélectionner une taille',
                'choices'  => [
                    'S' => 'S',
                    'M' => 'M',
                    'L' => 'L',
                    'XL' => 'XL',
                    'XXL' => 'XXL',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de séléctionner une taille"
                    ])
                ]
            ])
            ->add('gender', ChoiceType::class, [
                'required' => false,
                'label' => false,
                'placeholder' => 'Sélectionner un genre',
                'choices'  => [
                    'Homme' => 'homme',
                    'Femme' => 'femme',
                    'Mixte' => 'Mixte',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de séléctionner un genre"
                    ])
                ]
            ])
            ->add('picture', FileType::class, [
                'required' => false,
                'mapped' => false,
                'label' => false,
                'attr' => [
                    'class' => 'file-input'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de télécharger une photo"
                    ]),
                    new File(
                        maxSize: '5000k',
                        mimeTypes: [
                        'image/*',
                        ],
                        mimeTypesMessage: 'Fichier trop volumineux',
                    )
                ]
            ])
            ->add('price', NumberType::class, [
                'required' => false,
                'label' => false,
                'invalid_message' => "Merci de saisir une valeur numérique",
                'attr' => [
                    'class' => 'input ',
                    'placeholder' => "Prix"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de saisir le prix de l'article"
                    ]),
                ]
            ])
            ->add('stock', NumberType::class, [
                'required' => false,
                'label' => false,
                'invalid_message' => "Merci de saisir une valeur numérique",
                'attr' => [
                    'class' => 'input ',
                    'placeholder' => "Stock"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de saisir le stoc de l'article"
                    ]),
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => false,
                'class' => Category::class,
                'choice_label' => 'name',
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
