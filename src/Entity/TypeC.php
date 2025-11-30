<?php

namespace App\Entity;

use App\Repository\TypeCRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;



#[ORM\Entity(repositoryClass: TypeCRepository::class)]
class TypeC
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?float $montant_max = null;

    #[ORM\Column]
    private ?float $montant_min = null;

    #[ORM\OneToMany(targetEntity: Carte::class, mappedBy: 'type')]
    private Collection $cartes;

    public function __construct()
    {
        $this->cartes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getMontantMax(): ?float
    {
        return $this->montant_max;
    }

    public function setMontantMax(float $montant_max): static
    {
        $this->montant_max = $montant_max;

        return $this;
    }

    public function getMontantMin(): ?float
    {
        return $this->montant_min;
    }

    public function setMontantMin(float $montant_min): static
    {
        $this->montant_min = $montant_min;

        return $this;
    }

    /**
     * @return Collection<int, Carte>
     */
    public function getCartes(): Collection
    {
        return $this->cartes;
    }

    public function addCarte(Carte $carte): static
    {
        if (!$this->cartes->contains($carte)) {
            $this->cartes->add($carte);
            $carte->setType($this);
        }

        return $this;
    }

    public function removeCarte(Carte $carte): static
    {
        if ($this->cartes->removeElement($carte)) {
            // set the owning side to null (unless already changed)
            if ($carte->getType() === $this) {
                $carte->setType(null);
            }
        }

        return $this;
    }
}
