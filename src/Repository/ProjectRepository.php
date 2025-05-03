<?php

namespace App\Repository;

use App\Entity\Project;
use App\Enum\ProjectStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }
    
    /**
     * Find active projects (not cancelled or completed)
     * 
     * @return Project[]
     */
    public function findActiveProjects()
    {
        return $this->createQueryBuilder('p')
            ->where('p.status != :cancelled')
            ->andWhere('p.status != :completed')
            ->setParameter('cancelled', ProjectStatus::CANCELLED)
            ->setParameter('completed', ProjectStatus::COMPLETED)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Find projects by status
     * 
     * @param ProjectStatus $status
     * @return Project[]
     */
    public function findByStatus(ProjectStatus $status)
    {
        return $this->createQueryBuilder('p')
            ->where('p.status = :status')
            ->setParameter('status', $status)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Find projects by client
     * 
     * @param int $clientId
     * @return Project[]
     */
    public function findByClient(int $clientId)
    {
        return $this->createQueryBuilder('p')
            ->where('p.client = :clientId')
            ->setParameter('clientId', $clientId)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Find projects by manager
     * 
     * @param int $managerId
     * @return Project[]
     */
    public function findByManager(int $managerId)
    {
        return $this->createQueryBuilder('p')
            ->where('p.manager = :managerId')
            ->setParameter('managerId', $managerId)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}