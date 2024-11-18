<?php

namespace App\Form;

use App\Entity\Medecin;
use App\Entity\Patient;
use App\Repository\MedecinRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PatientType extends AbstractType
{

    public function __construct(private MedecinRepository $medecinRepository) {
        
    }

    public function getMedecin():array{
        $atb_choice=[];
        $medecins= $this->medecinRepository->findAll();
        foreach($medecins as $medecin){
            $atb_choice[$medecin->getNom().' '.$medecin->getPrenom()]=$medecin->getId();
        }
        return $atb_choice;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                "label"=>"Votre Nom",
                "attr"=>["class"=>"form-control m-2"]
            ])
            ->add('prenom',TextType::class,[
                "label"=> "Votre prenom",
                "attr"=>["class"=>"form-control m-2"]
            ])
            ->add("socialSecurityNumber",TextType::class,[
                "label"=>"Votre numero de sécurité sociale",
                "attr"=>["class"=>"form-control m-2"],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre numero de securité sociale',
                    ]),
                    new Length([
                        'min' => 15,
                        'minMessage' => 'Le numero de securité sociale comporte 15 chiffres',
                        // max length allowed by Symfony for security reasons
                        'max' => 15,
                    ]),
                ],
            ])
            ->add("Medecin", ChoiceType::class,[
                "mapped"=>false,
                "label"=>"Choisir un medecin",
                "choices"=>$this->getMedecin(),
                "attr"=>["class"=>"form-control m-2"]
            ])
           /* ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
                */
            /* ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
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
            */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}
