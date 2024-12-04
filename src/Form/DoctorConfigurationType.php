<?php

namespace App\Form;

use App\Entity\DoctorConfiguration;
use App\Entity\Medecin;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class DoctorConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('limitPatientNumber',IntegerType::class,[
                "label"=>"Nombre de patient/jour",
                "attr"=>["class"=>"form-control m-2"],
                "label_attr"=>["class"=>"ms-3"],
                "row_attr"=>["class"=>"small"],
                "constraints"=>[
                    new NotBlank([
                        'message' => 'Veuillez saisir une limite',
                    ]),
                ]
            ])
            ->add('timeToStart', TimeType::class, [
                "label"=>"Heure de debut de consultation journalière ",
                'widget' => 'single_text',
                "attr"=>["class"=>"form-control m-2"],
                "label_attr"=>["class"=>"ms-3"],
                "row_attr"=>["class"=>"small"],
                "constraints"=>[
                    new NotBlank([
                        'message' => 'Veuillez saisir une heure',
                    ]),
                ]
            ])
            ->add('currentPatientNumber',IntegerType::class,[
                "label"=>"Nombre de patient traité présentement",
                "attr"=>["class"=>"form-control m-2"],
                "label_attr"=>["class"=>"ms-3"],
                "row_attr"=>["class"=>"small"],
                "constraints"=>[
                    new NotBlank([
                        'message' => 'Veuillez saisir votre adresse email',
                    ]),
                ]
            ])
            ->add('lastConsultation', DateType::class, [
                "label"=>"Votre dernière consultation",
                'widget' => 'single_text',
                "attr"=>["class"=>"form-control m-2"],
                "label_attr"=>["class"=>"ms-3"],
                "row_attr"=>["class"=>"small"],
                "constraints"=>[
                    new NotBlank([
                        'message' => 'Veuillez choisir une date',
                    ]),
                ]
            ])
            ->add('timeToEnd', TimeType::class, [
                "label"=>"Heure de fin de consultation journalière ",
                'widget' => 'single_text',
                "attr"=>["class"=>"form-control m-2"],
                "label_attr"=>["class"=>"ms-3"],
                "row_attr"=>["class"=>"small"],
                "constraints"=>[
                    new NotBlank([
                        'message' => 'Veuillez saisir une heure',
                    ]),
                ]
            ])
           /* ->add('medecin', EntityType::class, [
                'class' => Medecin::class,
                'choice_label' => 'id',
            ])
                */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DoctorConfiguration::class,
        ]);
    }
}
