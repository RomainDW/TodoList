<?php


namespace AppBundle\Command;

use AppBundle\Service\TaskService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class AnonymousUserCommand extends Command
{
    protected static $defaultName = 'app:old-tasks:anonymous-user';
    private $taskService;

    /**
     * AnonymousUserCommand constructor.
     * @param TaskService $taskService
     */
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('binds tasks that do not belong to anyone to an anonymous user')
            ->setHelp('This command binds tasks that do not belong to anyone to an anonymous user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Do you want to link tasks that do not belong to anyone to an anonymous user ?(y/N) ', false);
        if (!$helper->ask($input, $output, $question)) {
            return;
        }

        $message = $this->taskService->linkToAnonymous();

        $output->writeln($message);
    }
}
