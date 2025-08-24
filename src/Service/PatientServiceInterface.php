<?php

namespace App\Service;

use App\Entity\Patient;

interface PatientServiceInterface
{
    public function save(Patient $patient):Patient;
    public function delete(int $id):Patient;
    public function update(int $id, Patient $patient):Patient;
    public function getAllPatient():array;
    public function getPatient(int $id):Patient|null;
    public function getPatientBySocialsecurityNumber(string $securityNumber):Patient|null;
    public function getPatientByEmail(string $email):Patient|null;

}
