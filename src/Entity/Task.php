<?php

namespace App\Entity;

use App\Enum\TaskPriority;
use App\Enum\TaskStatus;
use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $dueDate = null;

    #[ORM\Column(type: "string", enumType: TaskStatus::class)]
    private TaskStatus $status;

    #[ORM\Column(type: "string", nullable: true, enumType: TaskPriority::class)]
    private ?TaskPriority $priority;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: "tasks")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    #[ORM\OneToMany(targetEntity: Interaction::class, mappedBy: "task")]
    private Collection $interactions;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "assignedTasks")]
    private ?User $assignee = null;

    #[ORM\Column(type: "datetime_immutable")]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $completedAt = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $estimatedHours = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $actualHours = null;

    #[ORM\Column(type: "json", nullable: true)]
    private ?array $tags = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->interactions = new ArrayCollection();
        $this->status = TaskStatus::TODO;
        $this->priority = TaskPriority::MEDIUM;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(?\DateTimeInterface $dueDate): static
    {
        $this->dueDate = $dueDate;
        return $this;
    }

    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    public function setStatus(TaskStatus $status): static
    {
        $oldStatus = $this->status;
        $this->status = $status;
        $this->updatedAt = new \DateTimeImmutable();

        // Mettre à jour automatiquement la date de complétion si la tâche est terminée
        if ($status === TaskStatus::DONE && $oldStatus !== TaskStatus::DONE) {
            $this->completedAt = new \DateTime();
        } elseif ($status !== TaskStatus::DONE) {
            $this->completedAt = null;
        }

        return $this;
    }

    public function getPriority(): ?TaskPriority
    {
        return $this->priority;
    }

    public function setPriority(?TaskPriority $priority): static
    {
        $this->priority = $priority;
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

    /**
     * @return Collection<int, Interaction>
     */
    public function getInteractions(): Collection
    {
        return $this->interactions;
    }

    public function addInteraction(Interaction $interaction): static
    {
        if (!$this->interactions->contains($interaction)) {
            $this->interactions->add($interaction);
            $interaction->setTask($this);
        }

        return $this;
    }

    public function removeInteraction(Interaction $interaction): static
    {
        if ($this->interactions->removeElement($interaction)) {
            // set the owning side to null (unless already changed)
            if ($interaction->getTask() === $this) {
                $interaction->setTask(null);
            }
        }

        return $this;
    }

    public function getAssignee(): ?User
    {
        return $this->assignee;
    }

    public function setAssignee(?User $assignee): static
    {
        $this->assignee = $assignee;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(): static
    {
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getCompletedAt(): ?\DateTimeInterface
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?\DateTimeInterface $completedAt): static
    {
        $this->completedAt = $completedAt;
        return $this;
    }

    public function getEstimatedHours(): ?int
    {
        return $this->estimatedHours;
    }

    public function setEstimatedHours(?int $estimatedHours): static
    {
        $this->estimatedHours = $estimatedHours;
        return $this;
    }

    public function getActualHours(): ?int
    {
        return $this->actualHours;
    }

    public function setActualHours(?int $actualHours): static
    {
        $this->actualHours = $actualHours;
        return $this;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(?array $tags): static
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * Ajoute un tag à la tâche
     */
    public function addTag(string $tag): static
    {
        $tags = $this->tags ?? [];
        if (!in_array($tag, $tags)) {
            $tags[] = $tag;
            $this->tags = $tags;
        }
        return $this;
    }

    /**
     * Retire un tag de la tâche
     */
    public function removeTag(string $tag): static
    {
        if (!$this->tags) {
            return $this;
        }

        $index = array_search($tag, $this->tags);
        if ($index !== false) {
            unset($this->tags[$index]);
            $this->tags = array_values($this->tags);
        }
        return $this;
    }

    /**
     * Vérifie si la tâche est en retard
     */
    public function isLate(): bool
    {
        if (!$this->dueDate || $this->status === TaskStatus::DONE || $this->status === TaskStatus::CANCELLED) {
            return false;
        }

        return $this->dueDate < new \DateTime();
    }

    /**
     * Vérifie si la tâche est terminée
     */
    public function isDone(): bool
    {
        return $this->status === TaskStatus::DONE;
    }

    /**
     * Marque la tâche comme terminée
     */
    public function markAsDone(): static
    {
        return $this->setStatus(TaskStatus::DONE);
    }

    /**
     * Marque la tâche comme en cours
     */
    public function markAsInProgress(): static
    {
        if ($this->status === TaskStatus::TODO) {
            $this->startDate = new \DateTime();
        }
        return $this->setStatus(TaskStatus::IN_PROGRESS);
    }
}