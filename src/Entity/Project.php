<?php

namespace App\Entity;

use App\Enum\ProjectStatus;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type: Types::STRING, enumType: ProjectStatus::class)]
    private ProjectStatus $status;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: "projects")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Person $client = null;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: "project", orphanRemoval: true)]
    private Collection $tasks;

    #[ORM\OneToMany(targetEntity: Interaction::class, mappedBy: "project")]
    private Collection $interactions;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "managedProjects")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $manager = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: Types::FLOAT, precision: 10, scale: 2, nullable: true)]
    private ?float $budget = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $reference = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $tags = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->tasks = new ArrayCollection();
        $this->interactions = new ArrayCollection();
        $this->status = ProjectStatus::PENDING;
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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getStatus(): ProjectStatus
    {
        return $this->status;
    }

    public function setStatus(ProjectStatus $status): static
    {
        $this->status = $status;
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function getClient(): ?Person
    {
        return $this->client;
    }

    public function setClient(?Person $client): static
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setProject($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getProject() === $this) {
                $task->setProject(null);
            }
        }

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
            $interaction->setProject($this);
        }

        return $this;
    }

    public function removeInteraction(Interaction $interaction): static
    {
        if ($this->interactions->removeElement($interaction)) {
            // set the owning side to null (unless already changed)
            if ($interaction->getProject() === $this) {
                $interaction->setProject(null);
            }
        }

        return $this;
    }

    public function getManager(): ?User
    {
        return $this->manager;
    }

    public function setManager(?User $manager): static
    {
        $this->manager = $manager;
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

    public function getBudget(): ?float
    {
        return $this->budget;
    }

    public function setBudget(?float $budget): static
    {
        $this->budget = $budget;
        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): static
    {
        $this->reference = $reference;
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
     * Ajoute un tag au projet
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
     * Retire un tag du projet
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
     * Vérifie si le projet est en cours
     */
    public function isInProgress(): bool
    {
        return $this->status === ProjectStatus::IN_PROGRESS;
    }

    /**
     * Vérifie si le projet est terminé
     */
    public function isCompleted(): bool
    {
        return $this->status === ProjectStatus::COMPLETED;
    }

    /**
     * Vérifie si le projet est en attente
     */
    public function isPending(): bool
    {
        return $this->status === ProjectStatus::PENDING;
    }

    /**
     * Vérifie si le projet est annulé
     */
    public function isCancelled(): bool
    {
        return $this->status === ProjectStatus::CANCELLED;
    }
}