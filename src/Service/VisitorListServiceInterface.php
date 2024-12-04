<?php

namespace App\Service;

use App\Entity\Medecin;
use App\Entity\VisitorList;

interface VisitorListServiceInterface
{

    public function save(array $data):VisitorList;

    public function getVisitorListOfToday(VisitorList $visitor): array;
    public function getAllVisitorListByMedecin(Medecin $medecin): array|null;
}
