<?php

namespace App\Service;

use App\Entity\DoctorConfiguration;
use App\Entity\Medecin;
use App\Repository\DoctorConfigurationRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctorConfigurationService implements DoctorConfigurationServiceInterface
{

    public function __construct(
        private EntityManagerInterface $em,
        private DoctorConfigurationRepository $doctorConfigurationRepository
     ){

    }
    public function saveDoctorConfigurationByDefaut(Medecin $medecin): DoctorConfiguration{
        $time= new \DateTime();
        $end= new \DateTime();
        $end->setTime(12,0,0);
        $time->setTime(7,0,0);
        $conf = new DoctorConfiguration();
        $conf->setMedecin($medecin)
        ->setTimeToStart($time)->setCurrentPatientNumber(0)
        ->setLimitPatientNumber(40)->setLastConsultation(new \DateTime())
        ->setTimeToEnd($end);
        $this->em->persist($conf);
        $this->em->flush();
        return $conf;
    }
    public function getDoctorConfiguration(Medecin $medecin): DoctorConfiguration|null{
        $doctorConfiguration = $this->doctorConfigurationRepository->findOneBy(["medecin"=> $medecin]);
        return $doctorConfiguration;
    }
    public function updateDoctorConfiguration(int $id, DoctorConfiguration $doctorConfiguration): DoctorConfiguration{
        $doctorConfig=$this->doctorConfigurationRepository->find($id);
        $doctorConfig->setCurrentPatientNumber($doctorConfiguration->getCurrentPatientNumber());
        $doctorConfig->setLimitPatientNumber($doctorConfiguration->getLimitPatientNumber());
        $doctorConfig->setTimeToStart($doctorConfiguration->getTimeToStart())
        ->setTimeToEnd($doctorConfiguration->getTimeToEnd());
        $doctorConfig->setLastConsultation($doctorConfiguration->getLastConsultation());
        $doctorConfig->setMedecin($doctorConfiguration->getMedecin());
        $this->em->persist($doctorConfig);
        $this->em->flush();
        return $doctorConfig;
    }
    public function saveDoctorConfiguration(DoctorConfiguration $doctorConfiguration): DoctorConfiguration{

        $this->em->persist($doctorConfiguration);
        $this->em->flush();
        return $doctorConfiguration;
    }
    public function deleteDoctorConfiguration(Medecin $medecin): DoctorConfiguration{
        $conf = $this->doctorConfigurationRepository->findOneBy(["medecin"=> $medecin]);
        $this->em->remove($conf);
        $this->em->flush();
        return $conf;
    }
    public function deleteDoctorConfigurationById(int $id): DoctorConfiguration{

        $conf = $this->doctorConfigurationRepository->find($id);
        $this->em->remove($conf);
        $this->em->flush();
        return $conf;
    }
}
