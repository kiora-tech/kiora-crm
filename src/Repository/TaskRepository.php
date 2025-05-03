<?php

namespace App\Repository;

use App\Entity\Task;
use App\Enum\TaskStatus;
use App\Enum\TaskPriority;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }
    
    /**
     * Find tasks by status
     * 
     * @param TaskStatus $status
     * @return Task[]
     */
    public function findByStatus(TaskStatus $status)
    {
        return $this->createQueryBuilder('t')
            ->where('t.status = :status')
            ->setParameter('status', $status)
            ->orderBy('t.dueDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Find tasks by project
     * 
     * @param int $projectId
     * @return Task[]
     */
    public function findByProject(int $projectId)
    {
        return $this->createQueryBuilder('t')
            ->where('t.project = :projectId')
            ->setParameter('projectId', $projectId)
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Find tasks by assignee
     * 
     * @param int $assigneeId
     * @return Task[]
     */
    public function findByAssignee(int $assigneeId)
    {
        return $this->createQueryBuilder('t')
            ->where('t.assignee = :assigneeId')
            ->setParameter('assigneeId', $assigneeId)
            ->orderBy('t.dueDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Find tasks by priority
     * 
     * @param TaskPriority $priority
     * @return Task[]
     */
    public function findByPriority(TaskPriority $priority)
    {
        return $this->createQueryBuilder('t')
            ->where('t.priority = :priority')
            ->setParameter('priority', $priority)
            ->orderBy('t.dueDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Find overdue tasks (due date in the past and not completed or cancelled)
     * 
     * @return Task[]
     */
    public function findOverdueTasks()
    {
        return $this->createQueryBuilder('t')
            ->where('t.dueDate < :now')
            ->andWhere('t.status != :done')
            ->andWhere('t.status != :cancelled')
            ->setParameter('now', new \DateTime())
            ->setParameter('done', TaskStatus::DONE)
            ->setParameter('cancelled', TaskStatus::CANCELLED)
            ->orderBy('t.dueDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Find upcoming tasks (due in the next X days)
     * 
     * @param int $days Number of days to look ahead
     * @return Task[]
     */
    public function findUpcomingTasks(int $days = 7)
    {
        $now = new \DateTime();
        $future = new \DateTime("+{$days} days");
        
        return $this->createQueryBuilder('t')
            ->where('t.dueDate >= :now')
            ->andWhere('t.dueDate <= :future')
            ->andWhere('t.status != :done')
            ->andWhere('t.status != :cancelled')
            ->setParameter('now', $now)
            ->setParameter('future', $future)
            ->setParameter('done', TaskStatus::DONE)
            ->setParameter('cancelled', TaskStatus::CANCELLED)
            ->orderBy('t.dueDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
}