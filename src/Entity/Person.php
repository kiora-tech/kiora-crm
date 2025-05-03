<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap(["physical" => "PhysicalPerson", "legal" => "LegalPerson"])]
abstract class Person
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    protected ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank]
    protected ?string $name = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    protected ?string $email = null;

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    protected ?string $phone = null;

    #[ORM\Column(type: "text", nullable: true)]
    protected ?string $address = null;

    #[ORM\Column(type: "datetime_immutable")]
    protected ?\DateTimeImmutable $createdAt;

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    protected ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: "sourcePerson", targetEntity: Relation::class, orphanRemoval: true)]
    protected Collection $outgoingRelations;

    #[ORM\OneToMany(mappedBy: "targetPerson", targetEntity: Relation::class, orphanRemoval: true)]
    protected Collection $incomingRelations;

    #[ORM\OneToMany(mappedBy: "client", targetEntity: Project::class)]
    protected Collection $projects;

    #[ORM\OneToMany(mappedBy: "contact", targetEntity: Interaction::class)]
    protected Collection $interactions;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->outgoingRelations = new ArrayCollection();
        $this->incomingRelations = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->interactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
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

    /**
     * @return Collection<int, Relation>
     */
    public function getOutgoingRelations(): Collection
    {
        return $this->outgoingRelations;
    }

    public function addOutgoingRelation(Relation $relation): static
    {
        if (!$this->outgoingRelations->contains($relation)) {
            $this->outgoingRelations->add($relation);
            $relation->setSourcePerson($this);
        }

        return $this;
    }

    public function removeOutgoingRelation(Relation $relation): static
    {
        if ($this->outgoingRelations->removeElement($relation)) {
            // set the owning side to null (unless already changed)
            if ($relation->getSourcePerson() === $this) {
                $relation->setSourcePerson(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Relation>
     */
    public function getIncomingRelations(): Collection
    {
        return $this->incomingRelations;
    }

    public function addIncomingRelation(Relation $relation): static
    {
        if (!$this->incomingRelations->contains($relation)) {
            $this->incomingRelations->add($relation);
            $relation->setTargetPerson($this);
        }

        return $this;
    }

    public function removeIncomingRelation(Relation $relation): static
    {
        if ($this->incomingRelations->removeElement($relation)) {
            // set the owning side to null (unless already changed)
            if ($relation->getTargetPerson() === $this) {
                $relation->setTargetPerson(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setClient($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getClient() === $this) {
                $project->setClient(null);
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
            $interaction->setContact($this);
        }

        return $this;
    }

    public function removeInteraction(Interaction $interaction): static
    {
        if ($this->interactions->removeElement($interaction)) {
            // set the owning side to null (unless already changed)
            if ($interaction->getContact() === $this) {
                $interaction->setContact(null);
            }
        }

        return $this;
    }

    /**
     * Récupère les relations par type
     */
    public function getRelationsByType(string $type): array
    {
        return $this->outgoingRelations->filter(
            function (Relation $relation) use ($type) {
                return $relation->getType()->value === $type;
            }
        )->toArray();
    }

    /**
     * Récupère toutes les personnes liées à cette personne par un type de relation spécifique
     */
    public function getRelatedPersonsByType(string $type): array
    {
        $relations = $this->getRelationsByType($type);
        $persons = [];

        foreach ($relations as $relation) {
            $persons[] = $relation->getTargetPerson();
        }

        return $persons;
    }

    /**
     * Vérifie si cette personne est liée à une autre personne par un type de relation spécifique
     */
    public function isRelatedTo(Person $person, string $type): bool
    {
        foreach ($this->outgoingRelations as $relation) {
            if ($relation->getTargetPerson() === $person && $relation->getType()->value === $type) {
                return true;
            }
        }

        return false;
    }
}