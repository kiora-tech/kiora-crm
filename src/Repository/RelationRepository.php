<?php

namespace App\Repository;

use App\Entity\Relation;
use App\Enum\RelationType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Relation>
 */
class RelationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Relation::class);
    }
    
    /**
     * Find relations by type
     * 
     * @param RelationType $type
     * @return Relation[]
     */
    public function findByType(RelationType $type)
    {
        return $this->createQueryBuilder('r')
            ->where('r.type = :type')
            ->setParameter('type', $type)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Find relations by source person
     * 
     * @param int $sourcePersonId
     * @return Relation[]
     */
    public function findBySourcePerson(int $sourcePersonId)
    {
        return $this->createQueryBuilder('r')
            ->where('r.sourcePerson = :sourcePersonId')
            ->setParameter('sourcePersonId', $sourcePersonId)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Find relations by target person
     * 
     * @param int $targetPersonId
     * @return Relation[]
     */
    public function findByTargetPerson(int $targetPersonId)
    {
        return $this->createQueryBuilder('r')
            ->where('r.targetPerson = :targetPersonId')
            ->setParameter('targetPersonId', $targetPersonId)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Find relations by source and type
     * 
     * @param int $sourcePersonId
     * @param RelationType $type
     * @return Relation[]
     */
    public function findBySourceAndType(int $sourcePersonId, RelationType $type)
    {
        return $this->createQueryBuilder('r')
            ->where('r.sourcePerson = :sourcePersonId')
            ->andWhere('r.type = :type')
            ->setParameter('sourcePersonId', $sourcePersonId)
            ->setParameter('type', $type)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Find active relations
     * 
     * @return Relation[]
     */
    public function findActive()
    {
        return $this->createQueryBuilder('r')
            ->where('r.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Find all relations involving a person (as source or target)
     * 
     * @param int $personId
     * @return Relation[]
     */
    public function findAllForPerson(int $personId)
    {
        return $this->createQueryBuilder('r')
            ->where('r.sourcePerson = :personId')
            ->orWhere('r.targetPerson = :personId')
            ->setParameter('personId', $personId)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Find relationships between two specific persons
     * 
     * @param int $person1Id
     * @param int $person2Id
     * @return Relation[]
     */
    public function findBetweenPersons(int $person1Id, int $person2Id)
    {
        return $this->createQueryBuilder('r')
            ->where('(r.sourcePerson = :person1Id AND r.targetPerson = :person2Id)')
            ->orWhere('(r.sourcePerson = :person2Id AND r.targetPerson = :person1Id)')
            ->setParameter('person1Id', $person1Id)
            ->setParameter('person2Id', $person2Id)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}