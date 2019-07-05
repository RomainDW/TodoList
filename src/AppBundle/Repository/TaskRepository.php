<?php


namespace AppBundle\Repository;

use AppBundle\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TaskRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findByPage(int $page, int $maxResult)
    {
        $firstResult = ($page - 1) * $maxResult;
        return $this->createQueryBuilder('t')
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResult)
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $maxResult
     *
     * @return float
     *
     * @throws NonUniqueResultException
     */
    public function findMaxNumberOfPage(int $maxResult)
    {
        $req = $this->createQueryBuilder('t')
            ->select('COUNT(t)')
            ->getQuery()
            ->getSingleScalarResult();
        return ceil($req / $maxResult);
    }
}
