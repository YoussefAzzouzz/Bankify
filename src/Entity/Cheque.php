<?php

namespace App\Entity;

use App\Repository\ChequeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ChequeRepository::class)]
class Cheque
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Montant is required")]
    #[Assert\Positive(message: "Montant must be positive")]
    private ?float $montantC = null;

    #[ORM\ManyToOne(inversedBy: 'cheques')]
    #[Assert\NotBlank(message: "User selection is required")]
    private ?User $destinationC = null;

    #[ORM\ManyToOne(inversedBy: 'cheques')]
    #[Assert\NotBlank(message: "compte owner selection is required")]
    private ?CompteClient $compteID = null;

     /* #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateEmission = null; */

    /**
 * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
 */
private ?\DateTimeInterface $DateEmission = null;

    #[ORM\OneToMany(mappedBy: 'chequeID', targetEntity: Reclamtion::class)]
    private Collection $reclamtions;

    #[ORM\Column]
    private ?int $isfav = null;

    public function __construct()
    {
        $this->reclamtions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
   

    public function getMontantC(): ?float
    {
        return $this->montantC;
    }

    public function setMontantC(float $montantC): static
    {
        $this->montantC = $montantC;

        return $this;
    }

    public function getDestinationC(): ?User
    {
        return $this->destinationC;
    }

    public function setDestinationC(?User $destinationC): static
    {
        $this->destinationC = $destinationC;

        return $this;
    }

    public function getCompteID(): ?CompteClient
    {
        return $this->compteID;
    }

    public function setCompteID(?CompteClient $compteID): static
    {
        $this->compteID = $compteID;

        return $this;
    }

    public function getDateEmission(): ?\DateTimeInterface
    {
        return $this->DateEmission;
    }

    public function setDateEmission(\DateTimeInterface $DateEmission): static
    {
        $this->DateEmission = $DateEmission;

        return $this;
    }

    /**
     * @return Collection<int, Reclamtion>
     */
    public function getReclamtions(): Collection
    {
        return $this->reclamtions;
    }

    public function addReclamtion(Reclamtion $reclamtion): static
    {
        if (!$this->reclamtions->contains($reclamtion)) {
            $this->reclamtions->add($reclamtion);
            $reclamtion->setChequeID($this);
        }

        return $this;
    }

    public function removeReclamtion(Reclamtion $reclamtion): static
    {
        if ($this->reclamtions->removeElement($reclamtion)) {
            // set the owning side to null (unless already changed)
            if ($reclamtion->getChequeID() === $this) {
                $reclamtion->setChequeID(null);
            }
        }

        return $this;
    }

    public function getIsfav(): ?int
    {
        return $this->isfav;
    }

    public function setIsfav(int $isfav): static
    {
        $this->isfav = $isfav;

        return $this;
    }
}
