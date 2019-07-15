<?php


namespace Tests\AppBundle\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Tests\AppBundle\MyTestCase;

class AnonymousUserCommandTest extends MyTestCase
{
    public function testExecute()
    {
        $application = $this->application;

        $command = $application->find('app:old-tasks:anonymous-user');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs(['Y']);

        $commandTester->execute(['command'  => $command->getName()]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('Done.', $output);

        // test again (now there are no more tasks without users)
        $commandTester->setInputs(['Y']);
        $commandTester->execute(['command'  => $command->getName()]);
        $output = $commandTester->getDisplay();
        $this->assertContains('There are no tasks belonging to anyone', $output);

        // test without accepting
        $commandTester->setInputs(['N']);
        $commandTester->execute(['command'  => $command->getName()]);
        $output = $commandTester->getDisplay();
        $this->assertContains('Ok bye.', $output);
    }
}
