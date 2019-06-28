<?php


namespace Tests\AppBundle\Repository;

use AppBundle\Entity\Task;
use AppBundle\Repository\TaskRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    private $doctrine;

    protected function setUp()
    {
        $kernel = self::bootKernel();
        $this->doctrine = $kernel->getContainer()->get('doctrine');
        $this->entityManager = $this->doctrine->getManager();
    }

    public function testDependencies()
    {
        $this->assertInstanceOf(RegistryInterface::class, $this->doctrine);
        $taskRepository = new TaskRepository($this->doctrine);

        $this->assertInstanceOf(
            ServiceEntityRepositoryInterface::class,
            $taskRepository
        );
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
