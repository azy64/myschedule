<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Form\PatientType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InscriptionController extends AbstractController
{
    #[Route('/', name: 'app_inscription')]
    public function index(): Response
    {
        $patient =new Patient();
        $form = $this->createForm(PatientType::class,$patient);
        if($form->isSubmitted() && $form->isValid()){
            $medecin_id= $form->get('medecin')->getData();
        }


        return $this->render('inscription/index.html.twig', [
            'controller_name' => 'InscriptionController',
            'form'=>$form,
        ]);
    }
}
