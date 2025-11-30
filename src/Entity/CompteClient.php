<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\CompteClientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompteClientRepository::class)]
class CompteClient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[Assert\Type("alpha")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[Assert\Type("alpha")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 8, max:8)]
    private ?string $tel = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $mail = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 8, max: 16)]
    private ?string $rib = null;

    #[ORM\Column]
    private ?float $solde = null;

    #[ORM\OneToMany(targetEntity: Virement::class, mappedBy: 'id_compte')]
    private Collection $virements;

    #[ORM\ManyToOne(targetEntity: Type::class, inversedBy: 'compteClients')]
    #[ORM\JoinColumn(name: "type_name", referencedColumnName: "nom_type")]
    private ?Type $nom_type = null;

    #[ORM\ManyToOne(targetEntity: Pack::class, inversedBy: 'compteClients')]
    #[ORM\JoinColumn(name: "nom_pack", referencedColumnName: "nom_pack")]
    private ?Pack $nom_pack = null;

    

    public function __construct()
    {
        $this->virements = new ArrayCollection();
        $this->cheques = new ArrayCollection();
        $this->cartes = new ArrayCollection();


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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getRib(): ?string
    {
        return $this->rib;
    }

    public function setRib(string $rib): static
    {
        $this->rib = $rib;

        return $this;
    }

    public function getSolde(): ?float
    {
        return $this->solde;
    }

    public function setSolde(float $solde): static
    {
        $this->solde = $solde;

        return $this;
    }
    #[ORM\ManyToOne(inversedBy: 'comptes')]
    private ?User $UserID = null;
    public function getUserID(): ?User
    {
        return $this->UserID;
    }

    public function setUserID(?User $UserID): static
    {
        $this->UserID = $UserID;

        return $this;
    }
    #[ORM\OneToMany(targetEntity: Carte::class, mappedBy: 'account')]
    private Collection $cartes;
  

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
            $carte->setAccount($this);
        }

        return $this;
    }

    public function removeCarte(Carte $carte): static
    {
        if ($this->cartes->removeElement($carte)) {
            // set the owning side to null (unless already changed)
            if ($carte->getAccount() === $this) {
                $carte->setAccount(null);
            }
        }

        return $this;
    }
    



    /**
     * @return Collection<int, Virement>
     */
    public function getVirements(): Collection
    {
        return $this->virements;
    }

    public function addVirement(Virement $virement): static
    {
        if (!$this->virements->contains($virement)) {
            $this->virements->add($virement);
            $virement->setIdCompte($this);
        }

        return $this;
    }

    public function removeVirement(Virement $virement): static
    {
        if ($this->virements->removeElement($virement)) {
            // set the owning side to null (unless already changed)
            if ($virement->getIdCompte() === $this) {
                $virement->setIdCompte(null);
            }
        }

        return $this;
    }

    public function getNomType(): ?Type
    {
        return $this->nom_type;
    }

    public function setNomType(?Type $nom_type): static
    {
        $this->nom_type = $nom_type;

        return $this;
    }

    public function getNomPack(): ?Pack
    {
        return $this->nom_pack;
    }

    public function setNomPack(?Pack $nom_pack): static
    {
        $this->nom_pack = $nom_pack;

        return $this;
    }
    #[ORM\OneToMany(mappedBy: 'compteID', targetEntity: Cheque::class)]
    private Collection $cheques;
      /**
     * @return Collection<int, Cheque>
     */
    public function getCheques(): Collection
    {
        return $this->cheques;
    }

    public function addCheque(Cheque $cheque): static
    {
        if (!$this->cheques->contains($cheque)) {
            $this->cheques->add($cheque);
            $cheque->setCompteID($this);
        }

        return $this;
    }

    public function removeCheque(Cheque $cheque): static
    {
        if ($this->cheques->removeElement($cheque)) {
            // set the owning side to null (unless already changed)
            if ($cheque->getCompteID() === $this) {
                $cheque->setCompteID(null);
            }
        }

        return $this;
    }

   
}
