<?php

namespace App\Entity;

use App\Enum\RelationType;
use App\Repository\LegalPersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LegalPersonRepository::class)]
class LegalPerson extends Person
{
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $legalName = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $tradeName = null;

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $siret = null;

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $siren = null;

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $vatNumber = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $website = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $industry = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(type: "string", length: 2, nullable: true)]
    private ?string $countryCode = null;

    #[ORM\OneToMany(mappedBy: "company", targetEntity: User::class)]
    private Collection $users;

    public function __construct()
    {
        parent::__construct();
        $this->users = new ArrayCollection();
    }

    public function getLegalName(): ?string
    {
        return $this->legalName;
    }

    public function setLegalName(?string $legalName): static
    {
        $this->legalName = $legalName;
        // Si le nom commercial n'est pas défini, utiliser le nom légal comme nom principal
        if ($legalName && !$this->name) {
            $this->name = $legalName;
        }
        return $this;
    }

    public function getTradeName(): ?string
    {
        return $this->tradeName;
    }

    public function setTradeName(?string $tradeName): static
    {
        $this->tradeName = $tradeName;
        // Si le nom commercial est défini, l'utiliser comme nom principal
        if ($tradeName) {
            $this->name = $tradeName;
        }
        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): static
    {
        $this->siret = $siret;
        return $this;
    }

    public function getSiren(): ?string
    {
        return $this->siren;
    }

    public function setSiren(?string $siren): static
    {
        $this->siren = $siren;
        return $this;
    }

    public function getVatNumber(): ?string
    {
        return $this->vatNumber;
    }

    public function setVatNumber(?string $vatNumber): static
    {
        $this->vatNumber = $vatNumber;
        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website = $website;
        return $this;
    }

    public function getIndustry(): ?string
    {
        return $this->industry;
    }

    public function setIndustry(?string $industry): static
    {
        $this->industry = $industry;
        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;
        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): static
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setCompany($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCompany() === $this) {
                $user->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * Récupère les contacts de cette entreprise (PhysicalPerson liées par une relation SUBSIDIARY)
     */
    public function getContacts(): array
    {
        $contacts = [];

        foreach ($this->outgoingRelations as $relation) {
            if ($relation->getType() === RelationType::SUBSIDIARY && $relation->getTargetPerson() instanceof PhysicalPerson) {
                $contacts[] = $relation->getTargetPerson();
            }
        }

        return $contacts;
    }

    /**
     * Ajoute un contact à cette entreprise
     */
    public function addContact(PhysicalPerson $contact): static
    {
        // Vérifier si la relation existe déjà
        foreach ($this->outgoingRelations as $relation) {
            if ($relation->getTargetPerson() === $contact && $relation->getType() === RelationType::SUBSIDIARY) {
                return $this;
            }
        }

        // Créer la nouvelle relation
        $relation = new Relation();
        $relation->setSourcePerson($this);
        $relation->setTargetPerson($contact);
        $relation->setType(RelationType::SUBSIDIARY);
        $this->addOutgoingRelation($relation);

        return $this;
    }

    /**
     * Supprime un contact de cette entreprise
     */
    public function removeContact(PhysicalPerson $contact): static
    {
        foreach ($this->outgoingRelations as $relation) {
            if ($relation->getTargetPerson() === $contact && $relation->getType() === RelationType::SUBSIDIARY) {
                $this->removeOutgoingRelation($relation);
                break;
            }
        }

        return $this;
    }

    /**
     * Récupère le contact principal de cette entreprise
     */
    public function getPrimaryContact(): ?PhysicalPerson
    {
        foreach ($this->getContacts() as $contact) {
            if ($contact->isPrimaryContact()) {
                return $contact;
            }
        }

        return null;
    }

    /**
     * Récupère les entreprises liées (relations de type PARENT_COMPANY ou SUBSIDIARY)
     */
    public function getRelatedCompanies(): array
    {
        $companies = [];

        // Relations sortantes vers d'autres entreprises
        foreach ($this->outgoingRelations as $relation) {
            if (($relation->getType() === RelationType::PARENT_COMPANY || $relation->getType() === RelationType::SUBSIDIARY)
                && $relation->getTargetPerson() instanceof LegalPerson) {
                $companies[] = $relation->getTargetPerson();
            }
        }

        // Relations entrantes d'autres entreprises
        foreach ($this->incomingRelations as $relation) {
            if (($relation->getType() === RelationType::PARENT_COMPANY || $relation->getType() === RelationType::SUBSIDIARY)
                && $relation->getSourcePerson() instanceof LegalPerson) {
                $companies[] = $relation->getSourcePerson();
            }
        }

        return $companies;
    }
}