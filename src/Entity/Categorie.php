<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, Haie>
     */
    #[ORM\OneToMany(targetEntity: Haie::class, mappedBy: 'categorie')]
    private Collection $haies;

    public function __construct()
    {
        $this->haies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, Haie>
     */
    public function getHaies(): Collection
    {
        return $this->haies;
    }

    public function addHaie(Haie $haie): static
    {
        if (!$this->haies->contains($haie)) {
            $this->haies->add($haie);
            $haie->setCategorie($this);
        }

        return $this;
    }

    public function removeHaie(Haie $haie): static
    {
        if ($this->haies->removeElement($haie)) {
            // set the owning side to null (unless already changed)
            if ($haie->getCategorie() === $this) {
                $haie->setCategorie(null);
            }
        }

        return $this;
    }
}
