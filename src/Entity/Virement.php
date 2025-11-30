<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\VirementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VirementRepository::class)]
class Virement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 16)]
    #[Assert\NotBlank]
    #[Assert\Length(exactly: 16)]
    #[Assert\Regex(pattern: '/^[0-9]*$/')]
    private ?string $compte_source = null;

    #[ORM\Column(length: 16)]
    #[Assert\NotBlank]
    #[Assert\Length(exactly: 16)]
    #[Assert\Regex(pattern: '/^[0-9]*$/')]
    private ?string $compte_destination = null;

    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'float')]
    private ?float $montant = null;

    #[ORM\Column(type: 'date')] // Changed 'datetime' to 'date'
    #[Assert\NotBlank]
    #[Assert\Type(type: '\DateTimeInterface')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heure = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompteSource(): ?string
    {
        return $this->compte_source;
    }

    public function setCompteSource(string $compte_source): static
    {
        $this->compte_source = $compte_source;

        return $this;
    }

    public function getCompteDestination(): ?string
    {
        return $this->compte_destination;
    }

    public function setCompteDestination(string $compte_destination): static
    {
        $this->compte_destination = $compte_destination;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getHeure(): ?\DateTimeInterface
    {
        return $this->heure;
    }

    public function setHeure(\DateTimeInterface $heure): static
    {
        $this->heure = $heure;

        return $this;
    }

    #[ORM\ManyToOne(inversedBy: 'virements')]
    private ?CompteClient $id_compte = null;

    public function getIdCompte(): ?CompteClient
    {
        return $this->id_compte;
    }

    public function setIdCompte(?CompteClient $id_compte): static
    {
        $this->id_compte = $id_compte;

        return $this;
    }
}
