<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr'=> ['class' => 'form-control'],
                'label' => 'Mail :'
            ])
            ->add('nom', TextType::class, [
                'attr'=> ['class' => 'form-control'],
                'label' => 'Nom :'
            ])
            ->add('prenom', TextType::class, [
                'attr'=> ['class' => 'form-control'],
                'label' => 'Prénom :'
            ])

            ->add('adresse', TextType::class, [
                'attr'=> ['class' => 'form-control'],
                'label' => 'Adresse :'
            ])
            ->add('ville', TextType::class, [
                'attr'=> ['class' => 'form-control'],
                'label' => 'Ville :'
            ])
            ->add('code_p', TextType::class, [
                'attr'=> ['class' => 'form-control'],
                'label' => 'Code Postal :'
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Vous êtes :',
                'choices' => [
                    'Une Entreprise' => 'ROLE_ENTREPRISE',
                    'Un Particulier' => 'ROLE_PARTICULIER',
                ],
                'multiple' => false,  // The user can only choose one role
                'expanded' => false,  // Will show a select dropdown
                'attr' => ['class' => 'form-control'],
                'mapped' => false, // Don't map this field directly to the entity
            ])
            /*
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue(['message' => 'You should agree to our terms.']),
                ],
            ])
            */
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir un mot de passe']),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limite }} caractères',
                        'max' => 4096,
                    ]),
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
