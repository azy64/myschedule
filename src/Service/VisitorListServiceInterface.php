<?php

namespace App\Service;

use App\Entity\VisitorList;

interface VisitorListServiceInterface
{

    public function save(array $data):VisitorList;

    public function getVisitorListOfToday(): array;
}
