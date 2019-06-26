<?php

use AppBundle\DataFixtures\AppFixtures;
use Behat\Behat\Context\Context;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;

class DoctrineContext implements Context
{
    private $entityManager;

    /**
     * DoctrineContext constructor.
     *
     * @param EntityManagerInterface       $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @BeforeScenario
     *
     * @throws ToolsException
     */
    public function initDatabase()
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $this->loadFixtures();
    }

    protected function loadFixtures()
    {
        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->entityManager, $purger);

        $dataFixtures = new AppFixtures();
        $loader = new Loader();
        $loader->addFixture($dataFixtures);

        $executor->execute($loader->getFixtures());
    }
}
