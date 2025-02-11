<?php

namespace App\Entity;

use App\Repository\PlanningRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PlanningRepository::class)]
#[ORM\HasLifecycleCallbacks] // Enable lifecycle callbacks
class Planning
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "The name cannot be blank.")]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: "The name must be at least {{ limit }} characters long.",
        maxMessage: "The name cannot be longer than {{ limit }} characters."
    )]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "The science field cannot be blank.")]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: "The science field must be at least {{ limit }} characters long.",
        maxMessage: "The science field cannot be longer than {{ limit }} characters."
    )]
    private ?string $science = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "The start time cannot be blank.")]
    #[Assert\Type(\DateTimeInterface::class, message: "The start time must be a valid date and time.")]
    private ?\DateTimeInterface $startTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "The end time cannot be blank.")]
    #[Assert\Type(\DateTimeInterface::class, message: "The end time must be a valid date and time.")]
    #[Assert\GreaterThan(
        propertyPath: "startTime",
        message: "The end time must be after the start time."
    )]
    private ?\DateTimeInterface $endTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $uploadedDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $modifiedDate = null;

    // Automatically set uploaded_date and modified_date when the entity is created
    public function __construct()
    {
        $this->uploadedDate = new \DateTime(); // Set uploaded_date to the current date and time
        $this->modifiedDate = new \DateTime(); // Set modified_date to the current date and time
    }

    // Automatically update modified_date when the entity is updated
    #[ORM\PreUpdate]
    public function setModifiedDateValue(): void
    {
        $this->modifiedDate = new \DateTime(); // Update modified_date to the current date and time
    }

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getScience(): ?string
    {
        return $this->science;
    }

    public function setScience(string $science): self
    {
        $this->science = $science;
        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;
        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;
        return $this;
    }

    public function getUploadedDate(): ?\DateTimeInterface
    {
        return $this->uploadedDate;
    }

    public function setUploadedDate(\DateTimeInterface $uploadedDate): self
    {
        $this->uploadedDate = $uploadedDate;
        return $this;
    }

    public function getModifiedDate(): ?\DateTimeInterface
    {
        return $this->modifiedDate;
    }

    public function setModifiedDate(\DateTimeInterface $modifiedDate): self
    {
        $this->modifiedDate = $modifiedDate;
        return $this;
    }
}