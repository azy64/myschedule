<?php

namespace App\Service;

use App\Entity\Medecin;

interface MedecinServiceInterface
{
    public function getMedecin(int $id):Medecin|null;
}
