<?php

namespace App\Form;

use App\Entity\Medecin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('prenom',TextType::class,[
            'label'=> 'Votre prenom',
            "attr"=>["class"=>"form-control"],
            'constraints'=>[
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
            ]
        ])
        ->add('nom',TextType::class,[
            'label'=> 'Votre nom',
            "attr"=>["class"=>"form-control"],
            'constraints'=>[
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
                new Regex("",)
            ]
        ])
        ->add('specialisation',ChoiceType::class,[
            'choices'=>[
                "Medecin generaliste"=>"Medecin generaliste",
                "Pediatre"=>"Pediatre",
                "Dentiste"=>"Dentiste",
            ],
            "label"=>"Votre specialisation",
            "attr"=>["class"=>"form-control"]
        ])
        ->add('adresse',TextType::class,[
            'label'=> 'Votre Adresse',
            "attr"=>["class"=>"form-control"],
            "constraints"=>[
                new NotBlank([
                    'message' => 'Saisissez un mot de passe',
                ]),
                new NotNull([
                    "message"=>"L'adresse n'est doit pas etre null"
                ]),
                new Length([
                    "min"=>6,
                ])
            ]
        ])
            ->add('email',EmailType::class,[
                'label'=> 'Votre Email',
                "attr"=>["class"=>"form-control"],
                "constraints"=>[
                    new NotBlank([
                        'message' => 'Entrez votre email',
                    ]),
                ]
            ])
        ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',"class"=>"form-control"],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Medecin::class,
        ]);
    }
}
