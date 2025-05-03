<?php

namespace App\Entity;

use App\Enum\RelationType;
use App\Repository\RelationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RelationRepository::class)]
class Relation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: "outgoingRelations")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Person $sourcePerson = null;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: "incomingRelations")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Person $targetPerson = null;

    #[ORM\Column(type: "string", enumType: RelationType::class)]
    private RelationType $type;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: "datetime_immutable")]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: "boolean")]
    private bool $isActive = true;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $source = null;

    #[ORM\Column(type: "json", nullable: true)]
    private ?array $metadata = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->type = RelationType::CONTACT;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSourcePerson(): ?Person
    {
        return $this->sourcePerson;
    }

    public function setSourcePerson(?Person $sourcePerson): static
    {
        $this->sourcePerson = $sourcePerson;
        return $this;
    }

    public function getTargetPerson(): ?Person
    {
        return $this->targetPerson;
    }

    public function setTargetPerson(?Person $targetPerson): static
    {
        $this->targetPerson = $targetPerson;
        return $this;
    }

    public function getType(): RelationType
    {
        return $this->type;
    }

    public function setType(RelationType $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;
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

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): static
    {
        $this->source = $source;
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
     * Vérifie si cette relation est de type CLIENT
     */
    public function isClient(): bool
    {
        return $this->type === RelationType::CLIENT;
    }

    /**
     * Vérifie si cette relation est de type PROSPECT
     */
    public function isProspect(): bool
    {
        return $this->type === RelationType::PROSPECT;
    }

    /**
     * Vérifie si cette relation est de type CONTACT
     */
    public function isContact(): bool
    {
        return $this->type === RelationType::CONTACT;
    }
}