<?php

namespace App\Entity;

use App\Repository\RemboursementRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RemboursementRepository::class)]
class Remboursement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Vous devez saisir un montant")]
    #[Assert\Positive(message:"Vous devez saisir un montant positive")]
    private ?float $montantR = null;

    #[ORM\Column]
    private ?float $montantRestant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateR = null;

    #[ORM\Column]
    private ?int $dureeRestante = null;

    #[ORM\ManyToOne(inversedBy: 'remboursements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Credit $credit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontantR(): ?float
    {
        return $this->montantR;
    }

    public function setMontantR(float $montantR): static
    {
        $this->montantR = $montantR;

        return $this;
    }

    public function getMontantRestant(): ?float
    {
        return $this->montantRestant;
    }

    public function setMontantRestant(float $montantRestant): static
    {
        $this->montantRestant = $montantRestant;

        return $this;
    }

    public function getDateR(): ?\DateTimeInterface
    {
        return $this->dateR;
    }

    public function setDateR(\DateTimeInterface $dateR): static
    {
        $this->dateR = $dateR;

        return $this;
    }

    public function getDureeRestante(): ?int
    {
        return $this->dureeRestante;
    }

    public function setDureeRestante(int $dureeRestante): static
    {
        $this->dureeRestante = $dureeRestante;

        return $this;
    }

    public function getCredit(): ?Credit
    {
        return $this->credit;
    }

    public function setCredit(?Credit $credit): static
    {
        $this->credit = $credit;

        return $this;
    }
}
