<?php

namespace App\Entity;

use App\Enum\InteractionType;
use App\Repository\InteractionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: InteractionRepository::class)]
class Interaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", enumType: InteractionType::class)]
    private InteractionType $type;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $dateTime;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $subject = null;

    #[ORM\Column(type: "text")]
    private ?string $content = null;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: "interactions")]
    private ?Project $project = null;

    #[ORM\ManyToOne(targetEntity: Task::class, inversedBy: "interactions")]
    private ?Task $task = null;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: "interactions")]
    private ?Person $contact = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "interactions")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: "datetime_immutable")]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: "boolean")]
    private bool $isOutgoing = true;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $endDateTime = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $outcome = null;

    #[ORM\Column(type: "json", nullable: true)]
    private ?array $metadata = null;

    public function __construct()
    {
        $this->dateTime = new \DateTime();
        $this->createdAt = new \DateTimeImmutable();
        $this->type = InteractionType::NOTE;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): InteractionType
    {
        return $this->type;
    }

    public function setType(InteractionType $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getDateTime(): \DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeInterface $dateTime): static
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;
        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): static
    {
        $this->task = $task;
        return $this;
    }

    public function getContact(): ?Person
    {
        return $this->contact;
    }

    public function setContact(?Person $contact): static
    {
        $this->contact = $contact;
        return $this;
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

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function isOutgoing(): bool
    {
        return $this->isOutgoing;
    }

    public function setIsOutgoing(bool $isOutgoing): static
    {
        $this->isOutgoing = $isOutgoing;
        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;
        return $this;
    }

    public function getEndDateTime(): ?\DateTimeInterface
    {
        return $this->endDateTime;
    }

    public function setEndDateTime(?\DateTimeInterface $endDateTime): static
    {
        $this->endDateTime = $endDateTime;
        return $this;
    }

    public function getOutcome(): ?string
    {
        return $this->outcome;
    }

    public function setOutcome(?string $outcome): static
    {
        $this->outcome = $outcome;
        return $this;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(?array $metadata): static
    {
        $this->metadata = $metadata;
        return $this;
    }

    /**
     * Récupère une métadonnée spécifique
     */
    public function getMetadataValue(string $key, mixed $default = null): mixed
    {
        if (!$this->metadata || !array_key_exists($key, $this->metadata)) {
            return $default;
        }

        return $this->metadata[$key];
    }

    /**
     * Définit une métadonnée spécifique
     */
    public function setMetadataValue(string $key, mixed $value): static
    {
        $metadata = $this->metadata ?? [];
        $metadata[$key] = $value;
        $this->metadata = $metadata;
        return $this;
    }

    /**
     * Vérifie si l'interaction est un appel téléphonique
     */
    public function isCall(): bool
    {
        return $this->type === InteractionType::CALL;
    }

    /**
     * Vérifie si l'interaction est un email
     */
    public function isEmail(): bool
    {
        return $this->type === InteractionType::EMAIL;
    }

    /**
     * Vérifie si l'interaction est une réunion
     */
    public function isMeeting(): bool
    {
        return $this->type === InteractionType::MEETING;
    }

    /**
     * Vérifie si l'interaction est une note
     */
    public function isNote(): bool
    {
        return $this->type === InteractionType::NOTE;
    }

    /**
     * Calcule la durée de l'interaction (pour les réunions)
     */
    public function getDuration(): ?\DateInterval
    {
        if (!$this->endDateTime) {
            return null;
        }

        return $this->dateTime->diff($this->endDateTime);
    }

    /**
     * Formatte la durée en heures et minutes
     */
    public function getFormattedDuration(): ?string
    {
        $interval = $this->getDuration();
        if (!$interval) {
            return null;
        }

        $hours = $interval->h + ($interval->days * 24);
        $minutes = $interval->i;

        return sprintf('%dh %02dm', $hours, $minutes);
    }
}