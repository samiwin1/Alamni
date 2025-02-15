<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $descrC = null;

    #[ORM\Column(length: 255)]
    private ?string $matiereC = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateC = null;

    /**
     * @var Collection<int, Devoir>
     */
    #[ORM\OneToMany(mappedBy: 'cours', targetEntity: Devoir::class, cascade: ['remove'])]
    private Collection $devoirs;

    public function __construct()
    {
        $this->devoirs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;
        return $this;
    }

    public function getDescrC(): ?string
    {
        return $this->descrC;
    }

    public function setDescrC(string $descrC): static
    {
        $this->descrC = $descrC;
        return $this;
    }

    public function getMatiereC(): ?string
    {
        return $this->matiereC;
    }

    public function setMatiereC(string $matiereC): static
    {
        $this->matiereC = $matiereC;
        return $this;
    }

    public function getDateC(): ?\DateTimeInterface
    {
        return $this->dateC;
    }

    public function setDateC(\DateTimeInterface $dateC): static
    {
        $this->dateC = $dateC;
        return $this;
    }

    /**
     * @return Collection<int, Devoir>
     */
    public function getDevoirs(): Collection
    {
        return $this->devoirs;
    }

    public function addDevoir(Devoir $devoir): static
    {
        if (!$this->devoirs->contains($devoir)) {
            $this->devoirs->add($devoir);
            $devoir->setCours($this);
        }

        return $this;
    }

    public function removeDevoir(Devoir $devoir): static
    {
        if ($this->devoirs->removeElement($devoir)) {
            // Set the owning side to null (unless already changed)
            if ($devoir->getCours() === $this) {
                $devoir->setCours(null);
            }
        }

        return $this;
    }
}
