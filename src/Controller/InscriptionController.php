<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\VisitorList;
use App\Form\PatientType;
use App\Repository\VisitorListRepository;
use App\Security\Voter\MedecinVoter;
use App\Service\MedecinServiceInterface;
use App\Service\PatientServiceInterface;
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
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class InscriptionController extends AbstractController
{
    #[Route('/', name: 'app_inscription')]
    public function index(
        MedecinServiceInterface $medecinService, 
        PatientServiceInterface $patientServiceInterface,
        VisitorListServiceInterface $visitorListServiceInterface,
        SessionInterface $session,
        Request $request,
        EntityManagerInterface $entityManagerInterface
        ): Response
    {
        $patient =new Patient();
        $form = $this->createForm(PatientType::class,$patient);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $medecin_id= $form->get('medecin')->getData();
            $medecin = $medecinService->getMedecin($medecin_id);
            $patient = $patientServiceInterface->save($patient);
            $one=$visitorListServiceInterface->save(["medecin"=>$medecin,"patient"=>$patient,"arrivalDate"=>new \DateTime()]);
            $session->set("visitorList",serialize($one));
            return $this->redirectToRoute("app_resultat");
        }


        return $this->render('inscription/index.html.twig', [
            'controller_name' => 'InscriptionController',
            'form'=>$form,
        ]);
    }

    #[Route('/resultat', name: 'app_resultat')]
    public function resultat(SessionInterface $session){
        $visitorList = unserialize($session->get('visitorList'));
       
        return $this->render(
                "inscription/mynumber.html.twig",[
                    "schedule"=>$visitorList,
                ]
                );
    }

    #[Route('/list-patient', name: 'app_visitor')]
    #[IsGranted("ROLE_USER")]
    public function listVisitor(VisitorListServiceInterface $visitorListServiceInterface):Response{
        $visitors = $visitorListServiceInterface->getVisitorListOfToday();
        $visitors2=[];
        $user= $this->getUser();
        foreach($visitors as $visitor){
            if($visitor->getMedecin()->getId()==$user->getId()){
                $visitors2[]=$visitor;
            }
        }
        return $this->render("inscription/liste.html.twig",[
            "visitors"=>$visitors
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
}
