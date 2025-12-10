<?php

namespace App\Controller;

use App\Entity\Medecin;
use App\Entity\Patient;
use App\Entity\VisitorList;
use App\Event\ConfirmEvent;
use App\Form\PatientType;
use App\Repository\VisitorListRepository;
use App\Security\Voter\MedecinVoter;
use App\Service\DoctorConfigurationServiceInterface;
use App\Service\MedecinServiceInterface;
use App\Service\PatientServiceInterface;
use App\Service\utilities\DoctorConfigurationServiceUtility;
use App\Service\VisitorListServiceInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Serializable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class InscriptionController extends AbstractController
{
    #[Route('/', name: 'app_inscription')]
    public function index(
        MedecinServiceInterface $medecinService, 
        PatientServiceInterface $patientServiceInterface,
        VisitorListServiceInterface $visitorListServiceInterface,
        SessionInterface $session,
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        DoctorConfigurationServiceUtility $doctorUtility,
        DoctorConfigurationServiceInterface $doctorConfigurationService
        ): Response
    {
        $patient =new Patient();
        $form = $this->createForm(PatientType::class,$patient);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $medecin_id= $form->get('medecin')->getData();
            $medecin = $medecinService->getMedecin($medecin_id);
            // we are checking if the limit is reached----------
            if($doctorUtility->checkLimitPatientNumberReached($medecin)===false)
               return $this->redirectToRoute("app_limit_number", ['id'=> $medecin->getId()]);
            
            $doctorUtility->reinitializeCurrentPatientNumber($medecin);
            $patient = $patientServiceInterface->save($patient);
            $one=$visitorListServiceInterface->save(["medecin"=>$medecin,"patient"=>$patient,"arrivalDate"=>new \DateTime()]);
            $medecin->getDoctorConfiguration()->setCurrentPatientNumber(
                $medecin->getDoctorConfiguration()->getCurrentPatientNumber()+1);
            $doctorConfigurationService->updateDoctorConfiguration(
                $medecin->getDoctorConfiguration()->getId(),
                $medecin->getDoctorConfiguration()
            );
            $session->set("visitorList",serialize($one));
            return $this->redirectToRoute("app_resultat");
        }
        return $this->render('inscription/index.html.twig', [
            'controller_name' => 'InscriptionController',
            'form'=>$form,
        ]);
    }

    #[Route('/resultat', name: 'app_resultat')]
    public function resultat(SessionInterface $session, EventDispatcherInterface $eventDispatcherInterface,
     MailerInterface $mailerInterface): Response{
        $visitorList = unserialize($session->get('visitorList'));
        $eventDispatcherInterface->dispatch(new ConfirmEvent($visitorList, $mailerInterface));
        //dd($visitorList);
        return $this->render(
                "inscription/mynumber.html.twig",[
                    "schedule"=>$visitorList,
                ]
                );
    }

    #[Route('/list-patient', name: 'app_visitor')]
    #[IsGranted("ROLE_USER")]
    public function listVisitor(VisitorListServiceInterface $visitorListServiceInterface):Response{
        $user= $this->getUser();
        $medecin = $user instanceof Medecin ? $user : null;
        $visitors = $medecin && $medecin->getVisitorLists()[0]?$visitorListServiceInterface->getVisitorListOfToday($medecin->getVisitorLists()[0]):[];
        return $this->render("inscription/liste.html.twig",[
            "visitors"=>$visitors,"page"=>"list-patient","medecin_id"=>$medecin->getId()
        ]);
    }

    #[Route("/change-examined", name:"app_change_examined")]
    public function changeExamined(
        EntityManagerInterface $em, 
        Request $request,
        VisitorListRepository $visitorListRepository
    ):JsonResponse{
        $visitor_id=json_decode($request->getContent(),true);
        $visitorList = $visitorListRepository->find($visitor_id["id"]);
        $visitorList->setExamined(true);
        $em->persist($visitorList);
        $em->flush();
        return $this->json(["status"=> "success"]);

    }

    #[Route("/limit-patient-number/{id}", name:"app_limit_number")]
    public function limitPatientNumber(Medecin $medecin):Response{

        return $this->render("inscription/limit.html.twig",["medecin"=>$medecin]);
    }
    #[Route("/stats", name:"app_stats")]
    #[IsGranted("ROLE_USER")]
    public function statistique():Response{

        return $this->render("statistics/index.html.twig",["user"=> $this->getUser(),"page"=>"stats"]);
    }
    #[Route("/stats-medecin/{id}", name:"app_stats_medecin")]
    public function statisticData(Medecin $medecin):JsonResponse{

        return $this->json($medecin->getVisitorLists(),200,[],[
            "groups"=>["patient:read","visitorList:read"]
        ]);
    }

     #[Route('/api/v1/medecin/{id}/patients', name: 'app_medecin_patients', methods: ['GET'])]
    public function patients(Medecin $medecin, VisitorListServiceInterface $visitorListServiceInterface):Response{
        $visitors = $medecin && $medecin->getVisitorLists()[0]?$visitorListServiceInterface->getVisitorListOfToday($medecin->getVisitorLists()[0]):[];
        return $this->json($visitors,200,[],[
            "groups"=>["patient:read","visitorList:read"]
        ]);
    }
}
