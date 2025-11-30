<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type
{
    #[ORM\Id]
    #[ORM\Column(name: "nom_type", type: "string", length: 255)]
    #[Assert\NotBlank(message: 'Le nom du type ne peut pas être vide')]
    #[Assert\Length(max: 255, maxMessage: 'Le nom du type ne peut pas dépasser {{ limit }} caractères')]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+$/',
        message: 'Le nom du type ne peut contenir que des lettres.'
    )]
    private ?string $nomType = null;

    #[ORM\Column(length: 500)]
    #[Assert\NotBlank(message: 'La description ne peut pas être vide')]
    #[Assert\Length(max: 500, maxMessage: 'La description ne peut pas dépasser {{ limit }} caractères')]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: CompteClient::class, mappedBy: 'nom_type')]
    private Collection $compteClients;

    public function __construct()
    {
        $this->compteClients = new ArrayCollection();
    }

    public function getNomType(): ?string
    {
        return $this->nomType;
    }

    public function setNomType(string $nomType): static
    {
        $this->nomType = $nomType;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, CompteClient>
     */
    public function getCompteClients(): Collection
    {
        return $this->compteClients;
    }

    public function addCompteClient(CompteClient $compteClient): static
    {
        if (!$this->compteClients->contains($compteClient)) {
            $this->compteClients->add($compteClient);
            $compteClient->setNomType($this);
        }

        return $this;
    }

    public function removeCompteClient(CompteClient $compteClient): static
    {
        if ($this->compteClients->removeElement($compteClient)) {
            // set the owning side to null (unless already changed)
            if ($compteClient->getNomType() === $this) {
                $compteClient->setNomType(null);
            }
        }

        return $this;
    }
}
