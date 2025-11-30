<?php

namespace App\Entity;

use App\Repository\CategorieAssuranceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieAssuranceRepository::class)]
class CategorieAssurance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_categorie", type: "integer")]
    private ?int $id;

    #[ORM\Column(name: "nom_categorie", type: "string", length: 255)]
    private ?string $nomCategorie;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $description;

    #[ORM\Column(name: "TypeCouverture", type: "string", length: 255)]
    private ?string $typeCouverture;

    #[ORM\Column(name: "agenceResponsable", type: "string", length: 255)]
    private ?string $agenceResponsable;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCategorie(): ?string
    {
        return $this->nomCategorie;
    }

    public function setNomCategorie(?string $nomCategorie): self
    {
        $this->nomCategorie = $nomCategorie;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTypeCouverture(): ?string
    {
        return $this->typeCouverture;
    }

    public function setTypeCouverture(?string $typeCouverture): self
    {
        $this->typeCouverture = $typeCouverture;

        return $this;
    }

    public function getAgenceResponsable(): ?string
    {
        return $this->agenceResponsable;
    }

    public function setAgenceResponsable(?string $agenceResponsable): self
    {
        $this->agenceResponsable = $agenceResponsable;

        return $this;
    }
}
