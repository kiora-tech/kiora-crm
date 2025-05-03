<?php

namespace App\Repository;

use App\Entity\Interaction;
use App\Enum\InteractionType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Interaction>
 */
class InteractionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Interaction::class);
    }
    
    /**
     * Find interactions by type
     * 
     * @param InteractionType $type
     * @return Interaction[]
     */
    public function findByType(InteractionType $type)
    {
        return $this->createQueryBuilder('i')
            ->where('i.type = :type')
            ->setParameter('type', $type)
            ->orderBy('i.dateTime', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Find interactions by project
     * 
     * @param int $projectId
     * @return Interaction[]
     */
    public function findByProject(int $projectId)
    {
        return $this->createQueryBuilder('i')
            ->where('i.project = :projectId')
            ->setParameter('projectId', $projectId)
            ->orderBy('i.dateTime', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Find interactions by task
     * 
     * @param int $taskId
     * @return Interaction[]
     */
    public function findByTask(int $taskId)
    {
        return $this->createQueryBuilder('i')
            ->where('i.task = :taskId')
            ->setParameter('taskId', $taskId)
            ->orderBy('i.dateTime', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Find interactions by contact
     * 
     * @param int $contactId
     * @return Interaction[]
     */
    public function findByContact(int $contactId)
    {
        return $this->createQueryBuilder('i')
            ->where('i.contact = :contactId')
            ->setParameter('contactId', $contactId)
            ->orderBy('i.dateTime', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Find interactions by user
     * 
     * @param int $userId
     * @return Interaction[]
     */
    public function findByUser(int $userId)
    {
        return $this->createQueryBuilder('i')
            ->where('i.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('i.dateTime', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Find interactions in a date range
     * 
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return Interaction[]
     */
    public function findByDateRange(\DateTime $startDate, \DateTime $endDate)
    {
        return $this->createQueryBuilder('i')
            ->where('i.dateTime >= :startDate')
            ->andWhere('i.dateTime <= :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('i.dateTime', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Find recent interactions
     * 
     * @param int $limit
     * @return Interaction[]
     */
    public function findRecentInteractions(int $limit = 10)
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.dateTime', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}