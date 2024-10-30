<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\ClientInformation\Child;
use App\Entity\ClientInformation\Contract;
use App\Entity\ClientInformation\Expense;
use App\Entity\ClientInformation\FinancialAsset;
use App\Entity\ClientInformation\Insurance;
use App\Entity\ClientInformation\Liability;
use App\Entity\ClientInformation\NonFinancialAsset;
use App\Entity\ClientInformation\Person;
use App\Entity\ClientInformation\Revenue;
use App\Entity\ClientInformation\Saving;
use App\Entity\ClientInformation\Spouse;
use App\Entity\Traits\BirthPlaceTrait;
use App\Enum\MaritalSituation;
use App\Repository\ClientRepository;
use App\Utils\EntityIdInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
#[Gedmo\Loggable]
// #[AppAssert\UniqueClient]
class Client extends Person implements EntityIdInterface
{
    use SoftDeleteableEntity;
    use BirthPlaceTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 320, nullable: true)]
    //    #[Assert\NotBlank(message: 'email.not_blank')]
    #[Assert\Length(max: 320, maxMessage: 'email.max_length')]
    #[Assert\Email(message: 'email.invalid')]
    #[Gedmo\Versioned]
    private ?string $email = null;

    #[ORM\Column(length: 50, nullable: true)]
    //    #[Assert\NotBlank(message: 'phone.not_blank')]
    //    #[Assert\Regex(pattern: "/^\+\d{1,3}\d{7,14}$/", message: 'phone.invalid')]
    #[Gedmo\Versioned]
    private ?string $phone = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    //    #[Assert\NotBlank(message: 'adresse_number.not_blank')]
    #[Assert\Positive(message: 'adresse_number.invalid')]
    #[Gedmo\Versioned]
    private ?int $adresse_number = null;

    #[ORM\Column(length: 255, nullable: true)]
    //    #[Assert\NotBlank(message: 'adresse_street.not_blank')]
    #[Assert\Length(max: 255, maxMessage: 'adresse_street.max_length')]
    #[Assert\Regex(pattern: "/^[\p{L}\s\-]+$/u", message: 'adresse_street.invalid')]
    #[Gedmo\Versioned]
    private ?string $adresse_street = null;

    #[ORM\Column(length: 64, nullable: true)]
    //    #[Assert\NotBlank(message: 'adresse_postal_code.not_blank')]
    #[Assert\Length(max: 64, maxMessage: 'adresse_postal_code.max_length')]
    #[Assert\Regex(pattern: "/^\d+$/", message: 'adresse_postal_code.invalid')]
    #[Gedmo\Versioned]
    private ?string $adresse_postal_code = null;

    #[ORM\Column(length: 128, nullable: true)]
    //    #[Assert\NotBlank(message: 'adresse_city.not_blank')]
    #[Assert\Length(max: 128, maxMessage: 'adresse_city.max_length')]
    #[Assert\Regex(pattern: "/^[\p{L}\s\-]+$/u", message: 'adresse_city.invalid')]
    #[Gedmo\Versioned]
    private ?string $adresse_city = null;

    #[ORM\Column(length: 128, nullable: true)]
    //    #[Assert\NotBlank(message: 'adresse_country.not_blank')]
    #[Assert\Length(max: 128, maxMessage: 'adresse_country.max_length')]
    #[Assert\Regex(pattern: "/^[\p{L}\s\-]+$/u", message: 'adresse_country.invalid')]
    #[Gedmo\Versioned]
    private ?string $adresse_country = null;

    #[ORM\Column(length: 128, nullable: true)]
    //    #[Assert\NotBlank(message: 'nationality.not_blank')]
    #[Assert\Length(max: 128, maxMessage: 'nationality.max_length')]
    #[Assert\Regex(pattern: "/^[\p{L}\s\-]+$/u", message: 'nationality.invalid')]
    #[Gedmo\Versioned]
    private ?string $nationality = null;

    #[ORM\Column(options: ['default' => false])]
    //    #[Assert\NotNull(message: 'handicap.not_null')]
    #[Gedmo\Versioned]
    private bool $handicap = false;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, enumType: MaritalSituation::class)]
    //    #[Assert\NotBlank(message: 'marital_situation.not_blank')]
    #[Assert\Choice(callback: [MaritalSituation::class, 'cases'], message: 'marital_situation.invalid_choice')]
    #[Gedmo\Versioned]
    private ?MaritalSituation $marital_situation = null;

    /**
     * @var ArrayCollection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'clients')]
    private Collection $assignedCollaborators;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Gedmo\Versioned]
    /** @phpstan-ignore-next-line */
    protected $deletedAt;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $job = null;

    /**
     * @var ArrayCollection<int, ClientSigningDocumentSigner>
     */
    #[ORM\OneToMany(targetEntity: ClientSigningDocumentSigner::class, mappedBy: 'client', orphanRemoval: true)]
    private Collection $clientSigningDocumentSigners;

    /**
     * @var Collection<int, ClientDocument>
     */
    #[ORM\ManyToMany(targetEntity: ClientDocument::class, mappedBy: 'client')]
    private Collection $clientDocuments;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $jobCsp = null;

    #[ORM\Column(nullable: true)]
    private ?int $retirementAge = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Spouse $spouse = null;

    /**
     * @var ArrayCollection<int, Child>
     */
    #[ORM\OneToMany(targetEntity: Child::class, mappedBy: 'client', cascade: ['persist'])]
    private Collection $child;

    /**
     * @var array<string>|null
     */
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $objectives = [];

    #[ORM\Column(type: 'string', length: 1000, nullable: true)]
    #[Assert\Length(max: 1000)]
    private ?string $comment = null;

    /**
     * @var ArrayCollection<int, NonFinancialAsset>
     */
    #[ORM\OneToMany(targetEntity: NonFinancialAsset::class, mappedBy: 'client', cascade: [
        'persist',
        'remove',
    ], orphanRemoval: false)]
    private Collection $nonFinancialAssets;

    /**
     * @var ArrayCollection<int, FinancialAsset>
     */
    #[ORM\OneToMany(targetEntity: FinancialAsset::class, mappedBy: 'client', cascade: [
        'persist',
        'remove',
    ], orphanRemoval: false)]
    private Collection $financialAsset;

    /**
     * @var ArrayCollection<int, Liability>
     */
    #[ORM\OneToMany(targetEntity: Liability::class, mappedBy: 'client', cascade: [
        'persist',
        'remove',
    ], orphanRemoval: false)]
    private Collection $liabilities;

    /**
     * @var ArrayCollection<int, Revenue>
     */
    #[ORM\OneToMany(targetEntity: Revenue::class, mappedBy: 'client', cascade: [
        'persist',
        'remove',
    ], orphanRemoval: false)]
    private Collection $revenues;

    /**
     * @var ArrayCollection<int, Expense>
     */
    #[ORM\OneToMany(targetEntity: Expense::class, mappedBy: 'client', cascade: [
        'persist',
        'remove',
    ], orphanRemoval: false)]
    private Collection $expenses;

    /**
     * @var ArrayCollection<int, Contract>
     */
    #[ORM\OneToMany(targetEntity: Contract::class, mappedBy: 'client', cascade: [
        'persist',
        'remove',
    ], orphanRemoval: false)]
    private Collection $contract;

    /**
     * @var ArrayCollection<int, Insurance>
     */
    #[ORM\OneToMany(targetEntity: Insurance::class, mappedBy: 'client', cascade: [
        'persist',
        'remove',
    ], orphanRemoval: false)]
    private Collection $insurance;

    /**
     * @var ArrayCollection<int, Saving>
     */
    #[ORM\OneToMany(targetEntity: Saving::class, mappedBy: 'client', cascade: [
        'persist',
        'remove',
    ], orphanRemoval: false)]
    private Collection $saving;

    #[ORM\OneToOne(mappedBy: 'client', cascade: ['persist', 'remove'])]
    private ?ClientLogin $clientLogin = null;

    /**
     * @var Collection<int, SupportingDocuments>
     */
    #[ORM\OneToMany(targetEntity: SupportingDocuments::class, mappedBy: 'clients', cascade: [
        'persist',
        'remove',
    ], orphanRemoval: false)]
    private Collection $supportingDocuments;

    /**
     * @var Collection<int, ClientMetadata>
     */
    #[ORM\OneToMany(targetEntity: ClientMetadata::class, mappedBy: 'client', cascade: [
        'persist',
        'remove',
    ])]
    private Collection $clientMetadata;

    #[ORM\ManyToOne(inversedBy: 'clients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->assignedCollaborators = new ArrayCollection();
        $this->clientSigningDocumentSigners = new ArrayCollection();
        $this->clientDocuments = new ArrayCollection();
        $this->child = new ArrayCollection();
        $this->nonFinancialAssets = new ArrayCollection();
        $this->financialAsset = new ArrayCollection();
        $this->liabilities = new ArrayCollection();
        $this->revenues = new ArrayCollection();
        $this->expenses = new ArrayCollection();
        $this->contract = new ArrayCollection();
        $this->insurance = new ArrayCollection();
        $this->saving = new ArrayCollection();
        $this->supportingDocuments = new ArrayCollection();
        $this->clientMetadata = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAdresseNumber(): ?int
    {
        return $this->adresse_number;
    }

    public function setAdresseNumber(int $adresse_number): static
    {
        $this->adresse_number = $adresse_number;

        return $this;
    }

    public function getAdresseStreet(): ?string
    {
        return $this->adresse_street;
    }

    public function setAdresseStreet(string $adresse_street): static
    {
        $this->adresse_street = $adresse_street;

        return $this;
    }

    public function getAdressePostalCode(): ?string
    {
        return $this->adresse_postal_code;
    }

    public function setAdressePostalCode(string $adresse_postal_code): static
    {
        $this->adresse_postal_code = $adresse_postal_code;

        return $this;
    }

    public function getAdresseCity(): ?string
    {
        return $this->adresse_city;
    }

    public function setAdresseCity(string $adresse_city): static
    {
        $this->adresse_city = $adresse_city;

        return $this;
    }

    public function getAdresseCountry(): ?string
    {
        return $this->adresse_country;
    }

    public function setAdresseCountry(string $adresse_country): static
    {
        $this->adresse_country = $adresse_country;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): static
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function isHandicap(): ?bool
    {
        return $this->handicap;
    }

    public function setHandicap(bool $handicap): static
    {
        $this->handicap = $handicap;

        return $this;
    }

    public function getMaritalSituation(): ?MaritalSituation
    {
        return $this->marital_situation;
    }

    public function setMaritalSituation(?MaritalSituation $marital_situation): static
    {
        $this->marital_situation = $marital_situation;

        return $this;
    }

    /**
     * @return ArrayCollection<int, User>
     */
    public function getAssignedCollaborators(): Collection
    {
        return $this->assignedCollaborators;
    }

    public function addAssignedCollaborator(User $assignedCollaborator): static
    {
        if (!$this->assignedCollaborators->contains($assignedCollaborator)) {
            $this->assignedCollaborators->add($assignedCollaborator);
        }

        return $this;
    }

    public function removeAssignedCollaborator(User $assignedCollaborator): static
    {
        $this->assignedCollaborators->removeElement($assignedCollaborator);

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(string $job): static
    {
        $this->job = $job;

        return $this;
    }

    public function getJobCsp(): ?string
    {
        return $this->jobCsp;
    }

    public function setJobCsp(string $jobCsp): static
    {
        $this->jobCsp = $jobCsp;

        return $this;
    }

    public function getRetirementAge(): ?int
    {
        return $this->retirementAge;
    }

    public function setRetirementAge(int $retirementAge): static
    {
        $this->retirementAge = $retirementAge;

        return $this;
    }

    public function getSpouse(): ?Spouse
    {
        return $this->spouse;
    }

    public function setSpouse(?Spouse $spouse): static
    {
        $this->spouse = $spouse;

        return $this;
    }

    /**
     * @return ArrayCollection<int, Child>
     */
    public function getChild(): Collection
    {
        return $this->child;
    }

    public function addChild(Child $child): static
    {
        if (!$this->child->contains($child)) {
            $this->child->add($child);
            $child->setClient($this);
        }

        return $this;
    }

    public function removeChild(Child $child): static
    {
        if ($this->child->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getClient() === $this) {
                $child->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return array<string>|null
     */
    public function getObjectives(): ?array
    {
        return $this->objectives;
    }

    /**
     * @param array<string>|null $objectives
     */
    public function setObjectives(?array $objectives): static
    {
        $this->objectives = $objectives;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return ArrayCollection<int, NonFinancialAsset>
     */
    public function getNonFinancialAssets(): Collection
    {
        return $this->nonFinancialAssets;
    }

    public function addNonFinancialAsset(NonFinancialAsset $nonFinancialAsset): static
    {
        if (!$this->nonFinancialAssets->contains($nonFinancialAsset)) {
            $this->nonFinancialAssets->add($nonFinancialAsset);
            $nonFinancialAsset->setClient($this);
        }

        return $this;
    }

    public function removeNonFinancialAsset(NonFinancialAsset $nonFinancialAsset): static
    {
        if ($this->nonFinancialAssets->removeElement($nonFinancialAsset)) {
            // set the owning side to null (unless already changed)
            if ($nonFinancialAsset->getClient() === $this) {
                $nonFinancialAsset->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection<int, FinancialAsset>
     */
    public function getFinancialAsset(): Collection
    {
        return $this->financialAsset;
    }

    public function addFinancialAsset(FinancialAsset $financialAsset): static
    {
        if (!$this->financialAsset->contains($financialAsset)) {
            $this->financialAsset->add($financialAsset);
            $financialAsset->setClient($this);
        }

        return $this;
    }

    public function removeFinancialAsset(FinancialAsset $financialAsset): static
    {
        if ($this->financialAsset->removeElement($financialAsset)) {
            // set the owning side to null (unless already changed)
            if ($financialAsset->getClient() === $this) {
                $financialAsset->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection<int, Liability>
     */
    public function getLiabilities(): Collection
    {
        return $this->liabilities;
    }

    public function addLiability(Liability $liability): static
    {
        if (!$this->liabilities->contains($liability)) {
            $this->liabilities->add($liability);
            $liability->setClient($this);
        }

        return $this;
    }

    public function removeLiability(Liability $liability): static
    {
        if ($this->liabilities->removeElement($liability)) {
            // set the owning side to null (unless already changed)
            if ($liability->getClient() === $this) {
                $liability->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection<int, Revenue>
     */
    public function getRevenues(): Collection
    {
        return $this->revenues;
    }

    public function addRevenue(Revenue $revenue): static
    {
        if (!$this->revenues->contains($revenue)) {
            $this->revenues->add($revenue);
            $revenue->setClient($this);
        }

        return $this;
    }

    public function removeRevenue(Revenue $revenue): static
    {
        if ($this->revenues->removeElement($revenue)) {
            // set the owning side to null (unless already changed)
            if ($revenue->getClient() === $this) {
                $revenue->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection<int, Expense>
     */
    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    /**
     * @param ArrayCollection<int, Expense> $expense
     */
    public function setExpenses(Collection $expense): static
    {
        $this->expenses = $expense;

        return $this;
    }

    public function addExpense(Expense $expense): static
    {
        if (!$this->expenses->contains($expense)) {
            $this->expenses->add($expense);
            $expense->setClient($this);
        }

        return $this;
    }

    public function removeExpense(Expense $expense): static
    {
        if ($this->expenses->removeElement($expense)) {
            // set the owning side to null (unless already changed)
            if ($expense->getClient() === $this) {
                $expense->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection<int, Contract>
     */
    public function getContract(): Collection
    {
        return $this->contract;
    }

    /**
     * @param ArrayCollection<int, Contract> $contracts
     */
    public function setContract(Collection $contracts): static
    {
        $this->contract = $contracts;

        return $this;
    }

    public function addContract(Contract $contract): static
    {
        if (!$this->contract->contains($contract)) {
            $this->contract->add($contract);
            $contract->setClient($this);
        }

        return $this;
    }

    public function removeContract(Contract $contract): static
    {
        if ($this->contract->removeElement($contract)) {
            // set the owning side to null (unless already changed)
            if ($contract->getClient() === $this) {
                $contract->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection<int, Insurance>
     */
    public function getInsurance(): Collection
    {
        return $this->insurance;
    }

    public function addInsurance(Insurance $insurance): static
    {
        if (!$this->insurance->contains($insurance)) {
            $this->insurance->add($insurance);
            $insurance->setClient($this);
        }

        return $this;
    }

    public function removeInsurance(Insurance $insurance): static
    {
        if ($this->insurance->removeElement($insurance)) {
            // set the owning side to null (unless already changed)
            if ($insurance->getClient() === $this) {
                $insurance->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection<int, Saving>
     */
    public function getSaving(): Collection
    {
        return $this->saving;
    }

    public function addSaving(Saving $saving): static
    {
        if (!$this->saving->contains($saving)) {
            $this->saving->add($saving);
            $saving->setClient($this);
        }

        return $this;
    }

    public function removeSaving(Saving $saving): static
    {
        if ($this->saving->removeElement($saving)) {
            // set the owning side to null (unless already changed)
            if ($saving->getClient() === $this) {
                $saving->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection<int, ClientSigningDocumentSigner>
     */
    public function getClientSigningDocumentSigners(): Collection
    {
        return $this->clientSigningDocumentSigners;
    }

    public function addClientSigningDocumentSigner(ClientSigningDocumentSigner $clientSigningDocumentSigner): static
    {
        if (!$this->clientSigningDocumentSigners->contains($clientSigningDocumentSigner)) {
            $this->clientSigningDocumentSigners->add($clientSigningDocumentSigner);
            $clientSigningDocumentSigner->setClient($this);
        }

        return $this;
    }

    public function removeClientSigningDocumentSigner(ClientSigningDocumentSigner $clientSigningDocumentSigner): static
    {
        if ($this->clientSigningDocumentSigners->removeElement($clientSigningDocumentSigner)) {
            // set the owning side to null (unless already changed)
            if ($clientSigningDocumentSigner->getClient() === $this) {
                $clientSigningDocumentSigner->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ClientDocument>
     */
    public function getClientDocuments(): Collection
    {
        return $this->clientDocuments;
    }

    public function addClientDocument(ClientDocument $clientDocument): static
    {
        if (!$this->clientDocuments->contains($clientDocument)) {
            $this->clientDocuments->add($clientDocument);
            $clientDocument->addClient($this);
        }

        return $this;
    }

    public function removeClientDocument(ClientDocument $clientDocument): static
    {
        if ($this->clientDocuments->removeElement($clientDocument)) {
            $clientDocument->removeClient($this);
        }

        return $this;
    }

    public function getClientLogin(): ?ClientLogin
    {
        return $this->clientLogin;
    }

    public function setClientLogin(?ClientLogin $clientLogin): static
    {
        if (null === $clientLogin) {
            $this->clientLogin = null;

            return $this;
        }

        // set the owning side of the relation if necessary
        if ($clientLogin->getClient() !== $this) {
            $clientLogin->setClient($this);
        }

        $this->clientLogin = $clientLogin;

        return $this;
    }

    /**
     * @return Collection<int, SupportingDocuments>
     */
    public function getSupportingDocuments(): Collection
    {
        return $this->supportingDocuments;
    }

    public function addSupportingDocument(SupportingDocuments $supportingDocuments): static
    {
        if (!$this->supportingDocuments->contains($supportingDocuments)) {
            $this->supportingDocuments->add($supportingDocuments);
            $supportingDocuments->setClient($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, ClientMetadata>
     */
    public function getClientMetadata(): Collection
    {
        return $this->clientMetadata;
    }

    public function addClientMetadata(ClientMetadata $clientMetadata): static
    {
        if (!$this->clientMetadata->contains($clientMetadata)) {
            $this->clientMetadata->add($clientMetadata);
            $clientMetadata->setClient($this);
        }

        return $this;
    }

    public function removeSupportingDocument(SupportingDocuments $supportingDocuments): static
    {
        if ($this->supportingDocuments->removeElement($supportingDocuments) && $supportingDocuments->getClient() === $this) {
            $supportingDocuments->setClient(null);
        }

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function removeClientMetadata(ClientMetadata $clientMetadata): static
    {
        $this->clientMetadata->removeElement($clientMetadata);

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
