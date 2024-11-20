<?php

namespace App\Service;

use App\Entity\Medecin;
use App\Repository\MedecinRepository;

class MedecinService implements MedecinServiceInterface
{
    public function __construct(private MedecinRepository $medecinRepository) {
    }
    public function getMedecin(int $id): Medecin{
        return $this->medecinRepository->find($id);
    }

}
