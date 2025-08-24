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
            'label'=> 'Prenom',
            "attr"=>["class"=>"form-control"],
            "label_attr"=>["class"=>"ms-2 tunaweza-text-blue"],
            "row_attr"=>[
                "class"=>"text-secondary small",
                "style"=>""
            ],
            'constraints'=>[
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
            ]
        ])
        ->add('nom',TextType::class,[
            'label'=> 'Nom',
            "attr"=>["class"=>"form-control"],
            "label_attr"=>["class"=>"ms-2 tunaweza-text-blue"],
            "row_attr"=>[
                "class"=>"text-secondary small",
            ],
            'constraints'=>[
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
                new Regex("",)
            ]
        ])
        ->add('specialisation',ChoiceType::class,[
            'choices'=>[
                "Medecin généraliste"=>"Medecin généraliste",
                "Pédiatre"=>"Pédiatre",
                "Dentiste"=>"Dentiste",
            ],
            "label_attr"=>["class"=>"ms-2 tunaweza-text-blue"],
            "label"=>"Specialisation",
            "attr"=>["class"=>"form-control"],
            "row_attr"=>[
                "class"=>"text-secondary small",
            ],
        ])
        ->add('adresse',TextType::class,[
            'label'=> 'Adresse Postale',
            "attr"=>["class"=>"form-control"],
            "label_attr"=>["class"=>"ms-2 tunaweza-text-blue"],
            "row_attr"=>[
                "class"=>"text-secondary small",
            ],
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
                'label'=> 'Email',
                "attr"=>["class"=>"form-control"],
                "row_attr"=>[
                "class"=>"text-secondary small",
            ],
                "label_attr"=>["class"=>"ms-2 tunaweza-text-blue"],
                "constraints"=>[
                    new NotBlank([
                        'message' => 'Entrez votre email',
                    ]),
                ]
            ])
        ->add('agreeTerms', CheckboxType::class, [
                "label"=>"Accepter les conditions",
                'mapped' => false,
                "attr"=>["class"=>""],
                "row_attr"=>[
                "class"=>"text-secondary small",
            ],
                "label_attr"=>["class"=>"ms-2 tunaweza-text-blue"],
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                "label"=>"Mot de passe",
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',"class"=>"form-control"],
                "row_attr"=>[
                "class"=>"text-secondary small",
            ],
            "label_attr"=>["class"=>"ms-2 tunaweza-text-blue"],
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
