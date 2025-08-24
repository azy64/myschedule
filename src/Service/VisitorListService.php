<?php

namespace App\Service;

use App\Entity\Medecin;
use App\Entity\VisitorList;
use App\Repository\VisitorListRepository;
use Doctrine\DBAL\SQL\Parser\Visitor;
use Doctrine\ORM\EntityManagerInterface;

class VisitorListService implements VisitorListServiceInterface
{
    public function __construct(
        private EntityManagerInterface $manager, 
        private VisitorListRepository $visitorListRepository
        ) {
    }
    public function save(array $data): VisitorList   {
        $visitorList = new VisitorList();
        $visitorList->setMedecin($data["medecin"]);
        $visitorList->setPatient ($data["patient"]);
        $arrivalPosition = count($this->getVisitorListOfToday($visitorList))+1;       
        $visitorList->setArrivalDate($data["arrivalDate"]);
        $visitorList->setExamined(false);
        $visitorList->setArrivalPosition($arrivalPosition);
        $this->manager->persist($visitorList);
        $this->manager->flush();
        return $visitorList;
    }

    public function getVisitorListOfToday(VisitorList $visitorList): array{
        $today = new \DateTime();
        $visitors =[];
        $visitorLists = $this->visitorListRepository->findBy(["medecin"=>$visitorList->getMedecin()]);
        if($visitorLists!==null){
            foreach($visitorLists as $visitorList){
                if(
                    $visitorList->getArrivalDate()->format("Y-m-d") === $today->format("Y-m-d")
                    &&
                    $visitorList->isExamined()===false
                ){
                    $visitors[] = $visitorList;
                }
            }
        }
        return $visitors;
    }

    public function getAllVisitorListByMedecin(Medecin $medecin): array|null{
        return $this->visitorListRepository->findBy(["medecin"=>$medecin]);
    }
}
