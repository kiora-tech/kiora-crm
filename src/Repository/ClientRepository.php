<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\Company;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function findIncludeDeleted(mixed $id, LockMode|int|null $lockMode = null, ?int $lockVersion = null): ?Client
    {
        $this->getEntityManager()->getFilters()->disable('softdeleteable');

        /**
         * @var Client|null $result
         *
         * @phpstan-ignore-next-line
         */
        $result = $this->find($id, $lockMode, $lockVersion);

        return $result;
    }

    /**
     * @return Client[]
     */
    public function findExistingClient(Client $client): array
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.first_name = :firstName')
            ->andWhere('c.name = :name')
            ->setParameter('firstName', $client->getFirstName())
            ->setParameter('name', $client->getLastName());

        if (null !== $client->getId()) {
            $qb->andWhere('c.id != :id')
                ->setParameter('id', $client->getId());
        }

        /** @var Client[] $result */
        $result = $qb->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * @return Client[]
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function findByCompany(Company $company, bool $showDeleted = false): array
    {
        $query = $this->createQueryBuilder('c')
            ->andWhere('c.company = :company')
            ->setParameter('company', $company);

        if ($showDeleted) {
            $query->getEntityManager()->getFilters()->disable('softdeleteable');
        }

        /** @var Client[] $result */
        $result = $query
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * @return Client[]
     */
    public function findDeletedByCompany(Company $company): array
    {
        $query = $this->createQueryBuilder('c')
            ->andWhere('c.company = :company')
            ->andWhere('c.deletedAt IS NOT NULL')
            ->setParameter('company', $company);

        $query->getEntityManager()->getFilters()->disable('softdeleteable');

        /** @var Client[] $result */
        $result = $query
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * @return Client[]
     */
    public function findAssignedClients(User $user): array
    {
        /** @var Client[] $result */
        $result = $this->createQueryBuilder('c')
            ->andWhere(':user MEMBER OF c.assignedCollaborators')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * @param string[] $emails
     */
    public function countByEmails(array $emails): int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('c.email IN (:emails)')
            ->setParameter('emails', $emails)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countClientsForUserCreatedBetween(User $user, \DateTime $start, \DateTime $end): int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where(':user MEMBER OF c.assignedCollaborators')
            ->andWhere('c.createdAt >= :start')
            ->andWhere('c.createdAt <= :end')
            ->setParameter('user', $user)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getTotalAssetsByUser(User $user): float|int
    {
        $clients = $this->findAssignedClients($user);

        $totalFinancialAssets = 0;
        $totalNonFinancialAssets = 0;

        foreach ($clients as $client) {
            foreach ($client->getFinancialAsset() as $financialAsset) {
                $totalFinancialAssets += $financialAsset->getEstimatedValue() ?: 0;
            }

            foreach ($client->getNonFinancialAssets() as $nonFinancialAsset) {
                $totalNonFinancialAssets += $nonFinancialAsset->getEstimatedValue() ?: 0;
            }
        }

        return $totalFinancialAssets + $totalNonFinancialAssets;
    }
}
