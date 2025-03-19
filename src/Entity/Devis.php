<?php

namespace App\Entity;

use App\Repository\DevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Categorie; // added

#[ORM\Entity(repositoryClass: DevisRepository::class)]
class Devis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, Tailler>
     */
    #[ORM\OneToMany(targetEntity: Tailler::class, mappedBy: 'devis')]
    private Collection $taillers;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    private ?float $hauteur = null;

    private ?float $largeur = null;

    private ?string $prixHaie = null;

    // Remove the ORM mapping so it is not persisted:
    // #[ORM\ManyToOne(targetEntity: Categorie::class)]
    // #[ORM\JoinColumn(nullable: true)]
    private ?Categorie $categorie = null;

    public function __construct()
    {
        $this->taillers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Tailler>
     */
    public function getTaillers(): Collection
    {
        return $this->taillers;
    }

    public function addTailler(Tailler $tailler): static
    {
        if (!$this->taillers->contains($tailler)) {
            $this->taillers->add($tailler);
            $tailler->setDevis($this);
        }

        return $this;
    }

    public function removeTailler(Tailler $tailler): static
    {
        if ($this->taillers->removeElement($tailler)) {
            // set the owning side to null (unless already changed)
            if ($tailler->getDevis() === $this) {
                $tailler->setDevis(null);
            }
        }

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

    public function getHauteur(): ?float
    {
        return $this->hauteur;
    }

    public function setHauteur(?float $hauteur): static
    {
        $this->hauteur = $hauteur;
        return $this;
    }

    public function getLargeur(): ?float
    {
        return $this->largeur;
    }

    public function setLargeur(?float $largeur): static
    {
        $this->largeur = $largeur;
        return $this;
    }

    public function getPrixHaie(): ?string
    {
        return $this->prixHaie;
    }

    public function setPrixHaie(?string $prixHaie): static
    {
        $this->prixHaie = $prixHaie;
        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }
}
