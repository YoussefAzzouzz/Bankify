<?php

namespace App\Entity;

use App\Repository\AssuranceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AssuranceRepository::class)]
class Assurance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_assurance')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $typeAssurance = null;

    #[ORM\Column]
    private ?float $montantPrime = null;

    #[ORM\Column(length: 255)]
    private ?string $nomAssure = null;

    #[ORM\Column(length: 255)]
    private ?string $nomBeneficiaire = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $infoAssurance = null;

    #[ORM\ManyToOne(inversedBy: 'id_agence')]
    private ?Agence $agence = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeAssurance(): ?string
    {
        return $this->typeAssurance;
    }

    public function setTypeAssurance(string $typeAssurance): static
    {
        $this->typeAssurance = $typeAssurance;

        return $this;
    }

    public function getMontantPrime(): ?float
    {
        return $this->montantPrime;
    }

    public function setMontantPrime(float $montantPrime): static
    {
        $this->montantPrime = $montantPrime;

        return $this;
    }

    public function getNomAssure(): ?string
    {
        return $this->nomAssure;
    }

    public function setNomAssure(string $nomAssure): static
    {
        $this->nomAssure = $nomAssure;

        return $this;
    }

    public function getNomBeneficiaire(): ?string
    {
        return $this->nomBeneficiaire;
    }

    public function setNomBeneficiaire(string $nomBeneficiaire): static
    {
        $this->nomBeneficiaire = $nomBeneficiaire;

        return $this;
    }

    public function getInfoAssurance(): ?string
    {
        return $this->infoAssurance;
    }

    public function setInfoAssurance(?string $infoAssurance): static
    {
        $this->infoAssurance = $infoAssurance;

        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): static
    {
        $this->agence = $agence;

        return $this;
    }
    public static function findDistinctTypes(AssuranceRepository $repository): array
    {
        return $repository->findDistinctTypes();
    }
}
