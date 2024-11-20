<?php

namespace App\Service;

use App\Entity\DoctorConfiguration;
use App\Entity\Medecin;

interface DoctorConfigurationServiceInterface
{
    public function saveDoctorConfigurationByDefaut(Medecin $medecin): DoctorConfiguration;
    public function getDoctorConfiguration(Medecin $medecin): DoctorConfiguration|null;
    public function updateDoctorConfiguration(int $id, DoctorConfiguration $doctorConfiguration): DoctorConfiguration;
    public function saveDoctorConfiguration(DoctorConfiguration $doctorConfiguration): DoctorConfiguration;
    public function deleteDoctorConfiguration(Medecin $medecin): DoctorConfiguration;
    public function deleteDoctorConfigurationById(int $id): DoctorConfiguration;
}
