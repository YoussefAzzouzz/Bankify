<?php

namespace App\Entity;

use App\Repository\CategorieCreditRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CategorieCreditRepository::class)]
#[UniqueEntity(fields: ['nom'], message: "Cette catégorie existe déjà .")]
class CategorieCredit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255,unique: true)]
    #[Assert\NotBlank(message:"Vous devez saisir le nom de categorie")]
    private ?string $nom = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Vous devez saisir une montant minimale")]
    #[Assert\Expression("this.getMinMontant() < this.getMaxMontant()",message: 'Le montant minimal doit être inférieur à la montant maximale.')]
    private ?float $minMontant = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Vous devez saisir une montant maximale")]
    private ?float $maxMontant = null;

    #[ORM\OneToMany(targetEntity: Credit::class, mappedBy: 'categorie')]
    private Collection $credits;

    public function __construct()
    {
        $this->credits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getMinMontant(): ?float
    {
        return $this->minMontant;
    }

    public function setMinMontant(float $minMontant): static
    {
        $this->minMontant = $minMontant;

        return $this;
    }

    public function getMaxMontant(): ?float
    {
        return $this->maxMontant;
    }

    public function setMaxMontant(float $maxMontant): static
    {
        $this->maxMontant = $maxMontant;

        return $this;
    }

    /**
     * @return Collection<int, Credit>
     */
    public function getCredits(): Collection
    {
        return $this->credits;
    }

    public function addCredit(Credit $credit): static
    {
        if (!$this->credits->contains($credit)) {
            $this->credits->add($credit);
            $credit->setCategorie($this);
        }

        return $this;
    }

    public function removeCredit(Credit $credit): static
    {
        if ($this->credits->removeElement($credit)) {
            // set the owning side to null (unless already changed)
            if ($credit->getCategorie() === $this) {
                $credit->setCategorie(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string)$this->getNom();
    }
}
