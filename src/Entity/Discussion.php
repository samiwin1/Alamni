<?php

namespace App\Entity;

use App\Repository\DiscussionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DiscussionRepository::class)]
class Discussion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'adresse email est obligatoire.")]
    #[Assert\Email(message: "Veuillez entrer une adresse email valide.")]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le sujet est obligatoire.")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "Le sujet doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le sujet ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $sujet = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'adresse email du destinataire est obligatoire.")]
    #[Assert\Email(message: "Veuillez entrer une adresse email valide.")]
    private ?string $destinataire = null;

    #[ORM\Column(length: 2000)]
    #[Assert\NotBlank(message: "Le contenu est obligatoire.")]
    #[Assert\Length(
        min: 10,
        max: 2000,
        minMessage: "Le contenu doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le contenu ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $contenu = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du professeur est obligatoire.")]
    private ?string $professeur = null;


    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: "Le rôle est obligatoire.")]
    #[Assert\Choice(choices: ["parent", "enseignant", "eleve"], message: "Veuillez sélectionner un rôle valide.")]
    private ?string $role = null;


    #[ORM\Column(nullable: true)]
    #[Assert\Positive(message: "L'ID de l'élève doit être un nombre positif.")]
    private ?int $eleve = null;
    

    // Getters & Setters

    public function getId(): ?int { return $this->id; }

    public function getAdresse(): ?string { return $this->adresse; }
    public function setAdresse(string $adresse): static { $this->adresse = $adresse; return $this; }

    public function getSujet(): ?string { return $this->sujet; }
    public function setSujet(string $sujet): static { $this->sujet = $sujet; return $this; }

    public function getDestinataire(): ?string { return $this->destinataire; }
    public function setDestinataire(string $destinataire): static { $this->destinataire = $destinataire; return $this; }

    public function getContenu(): ?string { return $this->contenu; }
    public function setContenu(string $contenu): static { $this->contenu = $contenu; return $this; }

    public function getProfesseur(): ?string { return $this->professeur; }
    public function setProfesseur(string $professeur): static { $this->professeur = $professeur; return $this; }

    public function getRole(): ?string { return $this->role; }
    public function setRole(string $role): static { $this->role = $role; return $this; }

    public function getEleve(): ?int { return $this->eleve; }
    public function setEleve(int $eleve): static { $this->eleve = $eleve; return $this; }
}
