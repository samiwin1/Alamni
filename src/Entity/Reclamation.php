<?php
namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
     #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;
    
        #[ORM\Column(length: 255)]
        #[Assert\NotBlank(message: "L'email est obligatoire.")]
        #[Assert\Email(message: "Veuillez entrer un email valide.")]
        private ?string $user_email = null;
    
        #[ORM\Column(length: 255)]
        #[Assert\NotBlank(message: "Le destinataire est obligatoire.")]
        #[Assert\Email(message: "Veuillez entrer un email valide.")]
        private ?string $admin_mail = null;
    
        #[ORM\Column(length: 255)]
        #[Assert\NotBlank(message: "Veuillez sélectionner un rôle.")]
        #[Assert\Choice(choices: ["parent", "enseignant", "etudiant"], message: "Veuillez choisir un rôle valide.")]
        private ?string $role = null;
    
        #[ORM\Column(length: 255)]
        #[Assert\NotBlank(message: "L'objet est obligatoire.")]
        private ?string $objet = null;
    
        #[ORM\Column(length: 2000)]
        #[Assert\NotBlank(message: "Veuillez entrer une description.")]
        #[Assert\Length(min: 10, minMessage: "La description doit contenir au moins 10 caractères.")]
        private ?string $description = null;
    
        #[ORM\Column(type: Types::DATETIME_MUTABLE)]
        private ?\DateTimeInterface $date_soumission = null;
    
        #[ORM\Column(length: 255, options: ['default' => 'En attente'])]
        private ?string $status = 'En attente';  // ✅ Valeur par défaut ajoutée
    
        public function __construct()
        {
            $this->date_soumission = new \DateTime(); // ✅ Auto-remplissage de la date
            $this->status = 'En attente'; // ✅ Valeur par défaut
        }
    
    
    // ✅ Getters et Setters
    public function getId(): ?int { return $this->id; }
    public function getUserEmail(): ?string { return $this->user_email; }
    public function setUserEmail(string $user_email): static { $this->user_email = $user_email; return $this; }
    public function getAdminMail(): ?string { return $this->admin_mail; }
    public function setAdminMail(string $admin_mail): static { $this->admin_mail = $admin_mail; return $this; }
    public function getRole(): ?string { return $this->role; }
    public function setRole(string $role): static { $this->role = $role; return $this; }
    public function getObjet(): ?string { return $this->objet; }
    public function setObjet(string $objet): static { $this->objet = $objet; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): static { $this->description = $description; return $this; }
    public function getDateSoumission(): ?\DateTimeInterface { return $this->date_soumission; }
    public function getStatus(): ?string { return $this->status; }
    public function setStatus(string $status): static { $this->status = $status; return $this; }
}
