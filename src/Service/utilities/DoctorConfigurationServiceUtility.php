<?php

namespace App\Service\utilities;

use App\Entity\Medecin;
use App\Service\DoctorConfigurationServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DoctorConfigurationServiceUtility
{
    public function __construct(
            private DoctorConfigurationServiceInterface $doctorConfigurationService
        ){

    }

    public function checkLimitPatientNumberReached(Medecin $medecin) : bool{
        $doctorConfiguration = $this->doctorConfigurationService->getDoctorConfiguration($medecin);
        $today = new \DateTime();
        $time = $today->format("H:m:s");
        $time= explode(":",$time);
        $today->setTime($time[0], $time[1], $time[2]);
        $todayHour =(int) $today->format("H");
        $startHour= (int)$doctorConfiguration->getTimeToStart()->format("H");
        $endHour = (int) $doctorConfiguration->getTimeToEnd()->format("H");
        if(
            ($todayHour>=$startHour && $todayHour<=$endHour)
            &&
            ($doctorConfiguration->getCurrentPatientNumber()<= $doctorConfiguration->getLimitPatientNumber())
        )return true;
        return false;
    }

    public function reinitializeCurrentPatientNumber(Medecin $medecin) {
        $doctorConfiguration = $this->doctorConfigurationService->getDoctorConfiguration($medecin);
        $today = new \DateTime();
        if($doctorConfiguration->getLastConsultation()->format("Y-m-d")!==$today->format("Y-m-d")){
            $doctorConfiguration->setCurrentPatientNumber(0);
            $doctorConfiguration->setLastConsultation($today);
        }

    }

}
