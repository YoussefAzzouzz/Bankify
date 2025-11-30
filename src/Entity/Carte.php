<?php

namespace App\Entity;

use App\Repository\CarteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CarteRepository::class)]
#[UniqueEntity(fields: ['Num_C'], message: "Ce numero existe déjà .")]
class Carte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(unique: true)]
    #[Assert\NotBlank(message: "Vous devez saisir un numero")]

    private ?int $Num_C = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "Vous devez saisir une date")]
    private ?\DateTimeInterface $Date_Exp = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Vous devez saisir un network")]
    #[Assert\Choice(choices: ['visa', 'mastercard'], message: "Les networeks valides sont visa et mastercard.")]
    private ?string $Type_C = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Vous devez saisir un statue")]
    #[Assert\Choice(choices: ['active', 'bloquée', 'expirée'], message: "Les statues valides sont active, bloquée, expirée.")]
    private ?string $Statut_C = null;

    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'id_C')]
    private Collection $transactions;

    #[ORM\ManyToOne(inversedBy: 'cartes')]
    private ?CompteClient $account = null;

    #[ORM\ManyToOne(inversedBy: 'cartes')]
    private ?TypeC $type = null;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumC(): ?int
    {
        return $this->Num_C;
    }

    public function setNumC(int $Num_C): static
    {
        $this->Num_C = $Num_C;

        return $this;
    }

    public function getDateExp(): ?\DateTimeInterface
    {
        return $this->Date_Exp;
    }

    public function setDateExp(\DateTimeInterface $Date_Exp): static
    {
        $this->Date_Exp = $Date_Exp;

        return $this;
    }

    public function getTypeC(): ?string
    {
        return $this->Type_C;
    }

    public function setTypeC(string $Type_C): static
    {
        $this->Type_C = $Type_C;

        return $this;
    }

    public function getStatutC(): ?string
    {
        return $this->Statut_C;
    }

    public function setStatutC(string $Statut_C): static
    {
        $this->Statut_C = $Statut_C;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setIdC($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getIdC() === $this) {
                $transaction->setIdC(null);
            }
        }

        return $this;
    }

    public function getAccount(): ?CompteClient
    {
        return $this->account;
    }

    public function setAccount(?CompteClient $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function getType(): ?TypeC
    {
        return $this->type;
    }

    public function setType(?TypeC $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function updateStatus(): void
    {
        // Get the current date
        $currentDate = new \DateTime();

        // Compare expiration date with current date
        if ($this->Date_Exp < $currentDate) {
            // Set status to 'bloquée' if expiration date has passed
            $this->Statut_C = 'bloquée';
        }
    }
}
