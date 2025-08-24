<?php

namespace App\Entity;

use App\Repository\VisitorListRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VisitorListRepository::class)]
class VisitorList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["visitorList:read"])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'visitorLists')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Medecin $medecin = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    
    private ?Patient $patient = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["visitorList:read"])]
    private ?bool $examined = null;

    #[ORM\Column]
    #[Groups(["visitorList:read"])]
    private ?int $arrivalPosition = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["visitorList:read"])]
    private ?\DateTimeInterface $arrivalDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMedecin(): ?Medecin
    {
        return $this->medecin;
    }

    public function setMedecin(?Medecin $medecin): static
    {
        $this->medecin = $medecin;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function isExamined(): ?bool
    {
        return $this->examined;
    }

    public function setExamined(?bool $examined): static
    {
        $this->examined = $examined;

        return $this;
    }

    public function getArrivalPosition(): ?int
    {
        return $this->arrivalPosition;
    }

    public function setArrivalPosition(int $arrivalPosition): static
    {
        $this->arrivalPosition = $arrivalPosition;

        return $this;
    }

    public function getArrivalDate(): ?\DateTimeInterface
    {
        return $this->arrivalDate;
    }

    public function setArrivalDate(\DateTimeInterface $arrivalDate): static
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }
}
