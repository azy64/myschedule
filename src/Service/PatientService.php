<?php

namespace App\Service;

use App\Entity\Patient;
use App\Repository\PatientRepository;
use Doctrine\Persistence\ObjectManager;

class PatientService implements PatientServiceInterface
{

    public function __construct(private PatientRepository $patientRepository, private ObjectManager $manager) {
        
    }
    public function save(Patient $patient):Patient{
        $found=$this->getPatientBySocialsecurityNumber($patient->getSocialSecurityNumber());
        if(!$found){
            $this->manager->persist($patient);
            $this->manager->flush();
        }
        return $found;
    }
    public function getPatientBySocialsecurityNumber(string $securityNumber): Patient|null{
        return $this->patientRepository->findOneBy(["socialSecurityNumber"=>$securityNumber]);
    }

    public function delete(int $id): Patient{
        $patient = $this->patientRepository->find($id);
        $this->manager->remove($patient);
        return $patient;
    }

    public function update($id, Patient $patient): Patient{
        $patient1 = $this->patientRepository->find($id);
        $patient1->setSocialsecurityNumber($patient->getSocialSecurityNumber());
        $patient1->setNom($patient->getNom());
        $patient1->setPrenom($patient->getPrenom());
        $this->manager->persist($patient1);
        $this->manager->flush();
        return $patient1;
    }

    public function getAllPatient(): array{
        return $this->patientRepository->findAll();
    }

    public function getPatient(int $id): Patient|null{
        return $this->patientRepository->find($id);
    }
}
