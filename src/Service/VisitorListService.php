<?php

namespace App\Service;

use App\Entity\VisitorList;
use App\Repository\VisitorListRepository;
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
        $arrivalPosition = count($this->getVisitorListOfToday())+1;
        $visitorList->setMedecin($data["medecin"]);
        $visitorList->setPatient ($data["patient"]);
        $visitorList->setArrivalDate($data["arrivalDate"]);
        $visitorList->setExamined(false);
        $visitorList->setArrivalPosition($arrivalPosition);
        $this->manager->persist($visitorList);
        $this->manager->flush();
        return $visitorList;
    }

    public function getVisitorListOfToday(): array{
        $today = new \DateTime();
        $visitors =[];
        $visitorLists = $this->visitorListRepository->findAll();
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

}
