<?php


namespace Tests\AppBundle\Repository;

use AppBundle\Entity\Task;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testFindByPage()
    {
        $tasks = $this->entityManager
            ->getRepository(Task::class)
            ->findByPage(1, 2);

        $this->assertCount(2, $tasks);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testFindMaxNumberOfPage()
    {
        $result = $this->entityManager
            ->getRepository(Task::class)
            ->findMaxNumberOfPage(6);

        $this->assertTrue(is_float($result));
    }
}
