<?php

namespace App\Entity;

use App\Repository\DoctorConfigurationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DoctorConfigurationRepository::class)]
class DoctorConfiguration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $limitPatientNumber = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $timeToStart = null;

    #[ORM\Column]
    private ?int $currentPatientNumber = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $lastConsultation = null;

    #[ORM\OneToOne(inversedBy: 'doctorConfiguration', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Medecin $medecin = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $timeToEnd = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLimitPatientNumber(): ?int
    {
        return $this->limitPatientNumber;
    }

    public function setLimitPatientNumber(int $limitPatientNumber): static
    {
        $this->limitPatientNumber = $limitPatientNumber;

        return $this;
    }

    public function getTimeToStart(): ?\DateTimeInterface
    {
        return $this->timeToStart;
    }

    public function setTimeToStart(\DateTimeInterface $timeToStart): static
    {
        $this->timeToStart = $timeToStart;

        return $this;
    }

    public function getCurrentPatientNumber(): ?int
    {
        return $this->currentPatientNumber;
    }

    public function setCurrentPatientNumber(int $currentPatientNumber): static
    {
        $this->currentPatientNumber = $currentPatientNumber;

        return $this;
    }

    public function getLastConsultation(): ?\DateTimeInterface
    {
        return $this->lastConsultation;
    }

    public function setLastConsultation(\DateTimeInterface $lastConsultation): static
    {
        $this->lastConsultation = $lastConsultation;

        return $this;
    }

    public function getMedecin(): ?Medecin
    {
        return $this->medecin;
    }

    public function setMedecin(Medecin $medecin): static
    {
        $this->medecin = $medecin;

        return $this;
    }

    public function getTimeToEnd(): ?\DateTimeInterface
    {
        return $this->timeToEnd;
    }

    public function setTimeToEnd(\DateTimeInterface $timeToEnd): static
    {
        $this->timeToEnd = $timeToEnd;

        return $this;
    }
}
