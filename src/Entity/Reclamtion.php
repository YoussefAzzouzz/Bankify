<?php

namespace App\Entity;

use App\Repository\ReclamtionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ReclamtionRepository::class)]
class Reclamtion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "categorie selection is required")]
    private ?string $categorie = null;

    /*#[ORM\Column(length: 255)]
    private ?string $statutR = null; */

    #[ORM\Column(length: 255, options: ["default" => "En cours"])]
    #[Assert\NotBlank(message: "statut selection is required")]

      private ?string $statutR = null;

    #[ORM\ManyToOne(inversedBy: 'reclamtions')]
    private ?Cheque $chequeID = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getStatutR(): ?string
    {
        return $this->statutR;
    }

    public function setStatutR(string $statutR): static
    {
        $this->statutR = $statutR;

        return $this;
    }

    public function getChequeID(): ?Cheque
    {
        return $this->chequeID;
    }

    public function setChequeID(?Cheque $chequeID): static
    {
        $this->chequeID = $chequeID;

        return $this;
    }
}
