<?php

namespace App\Twig\Components;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('client_search')]
class ClientSearch
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $query = '';

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return Client[]
     */
    public function getResults(): array
    {
        if (empty($this->query)) {
            return [];
        }

        /** @var Client[] $result */
        $result = $this->entityManager->getRepository(Client::class)->createQueryBuilder('c')
            ->where('c.lastName LIKE :query OR c.firstName LIKE :query OR c.email LIKE :query OR c.phone LIKE :query')
            ->setParameter('query', '%'.$this->query.'%')
            ->getQuery()
            ->getResult();

        return $result;
    }
}
