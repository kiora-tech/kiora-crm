<?php

namespace App\Entity;

use App\Repository\PhysicalPersonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PhysicalPersonRepository::class)]
class PhysicalPerson extends Person
{
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $position = null;

    #[ORM\Column(type: "date", nullable: true)]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\Column(type: "boolean")]
    private bool $isManager = false;

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $mobile = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $isPrimaryContact = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $department = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $profilePicture = null;

    public function __construct()
    {
        parent::__construct();
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;
        // Mettre à jour le nom complet lorsque le prénom change
        $this->updateFullName();
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;
        // Mettre à jour le nom complet lorsque le nom de famille change
        $this->updateFullName();
        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): static
    {
        $this->position = $position;
        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function isManager(): bool
    {
        return $this->isManager;
    }

    public function setIsManager(bool $isManager): static
    {
        $this->isManager = $isManager;
        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): static
    {
        $this->mobile = $mobile;
        return $this;
    }

    public function isPrimaryContact(): ?bool
    {
        return $this->isPrimaryContact;
    }

    public function setIsPrimaryContact(?bool $isPrimaryContact): static
    {
        $this->isPrimaryContact = $isPrimaryContact;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): static
    {
        $this->department = $department;
        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): static
    {
        $this->profilePicture = $profilePicture;
        return $this;
    }

    /**
     * Met à jour le nom complet basé sur le prénom et le nom
     */
    private function updateFullName(): void
    {
        $fullName = trim(($this->firstName ?? '') . ' ' . ($this->lastName ?? ''));
        if (!empty($fullName)) {
            $this->name = $fullName;
        }
    }

    /**
     * Récupère l'entreprise employeur en cherchant la relation de type SUBSIDIARY vers une LegalPerson
     */
    public function getEmployer(): ?LegalPerson
    {
        foreach ($this->incomingRelations as $relation) {
            if ($relation->getType() === \App\Enum\RelationType::SUBSIDIARY && $relation->getSourcePerson() instanceof LegalPerson) {
                return $relation->getSourcePerson();
            }
        }

        return null;
    }

    /**
     * Définit l'entreprise employeur en créant une relation de type SUBSIDIARY
     */
    public function setEmployer(?LegalPerson $employer): static
    {
        // Supprimer les anciennes relations d'employeur
        foreach ($this->incomingRelations as $relation) {
            if ($relation->getType() === \App\Enum\RelationType::SUBSIDIARY && $relation->getSourcePerson() instanceof LegalPerson) {
                $this->removeIncomingRelation($relation);
            }
        }

        // Ajouter la nouvelle relation d'employeur
        if ($employer !== null) {
            $relation = new Relation();
            $relation->setSourcePerson($employer);
            $relation->setTargetPerson($this);
            $relation->setType(\App\Enum\RelationType::SUBSIDIARY);
            $this->addIncomingRelation($relation);
        }

        return $this;
    }
}