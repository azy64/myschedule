<?php

namespace App\Controller;

use App\Entity\DoctorConfiguration;
use App\Form\DoctorConfigurationType;
use App\Repository\DoctorConfigurationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/doctor/configuration')]
#[IsGranted("ROLE_USER")]
final class DoctorConfigurationController extends AbstractController
{
    #[Route(name: 'app_doctor_configuration_index', methods: ['GET'])]
    public function index(DoctorConfigurationRepository $doctorConfigurationRepository): Response
    {
        return $this->render('doctor_configuration/index.html.twig', [
            'doctor_configurations' => $doctorConfigurationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_doctor_configuration_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $doctorConfiguration = new DoctorConfiguration();
        $form = $this->createForm(DoctorConfigurationType::class, $doctorConfiguration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($doctorConfiguration);
            $entityManager->flush();

            return $this->redirectToRoute('app_doctor_configuration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('doctor_configuration/new.html.twig', [
            'doctor_configuration' => $doctorConfiguration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_doctor_configuration_show', methods: ['GET'])]
    public function show(DoctorConfiguration $doctorConfiguration): Response
    {
        return $this->render('doctor_configuration/show.html.twig', [
            'doctor_configuration' => $doctorConfiguration,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_doctor_configuration_edit', methods: ['GET', 'POST'])]
    
    public function edit(Request $request, DoctorConfiguration $doctorConfiguration, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DoctorConfigurationType::class, $doctorConfiguration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_doctor_configuration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('doctor_configuration/edit.html.twig', [
            'doctor_configuration' => $doctorConfiguration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_doctor_configuration_delete', methods: ['POST'])]
    public function delete(Request $request, DoctorConfiguration $doctorConfiguration, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$doctorConfiguration->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($doctorConfiguration);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_doctor_configuration_index', [], Response::HTTP_SEE_OTHER);
    }
}
