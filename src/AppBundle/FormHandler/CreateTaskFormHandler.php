<?php


namespace AppBundle\FormHandler;

use AppBundle\Entity\User;
use AppBundle\Service\TaskService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateTaskFormHandler
{
    /**
     * @var TaskService
     */
    private $taskService;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * CreateTaskFormHandler constructor.
     * @param TaskService $taskService
     * @param ValidatorInterface $validator
     * @param FlashBagInterface $flashBag
     */
    public function __construct(TaskService $taskService, ValidatorInterface $validator, FlashBagInterface $flashBag)
    {
        $this->taskService = $taskService;
        $this->validator = $validator;
        $this->flashBag = $flashBag;
    }

    public function handle(FormInterface $form, User $user): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $this->taskService->initTask($form->getData(), $user);
            if (count($errors = $this->validator->validate($task))) {
                foreach ($errors as $error) {
                    $this->flashBag->add('error', $error->getMessage());
                }
                return false;
            }
            $this->taskService->save($task);
            return true;
        }
        return false;
    }
}
