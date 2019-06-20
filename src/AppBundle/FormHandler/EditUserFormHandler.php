<?php


namespace AppBundle\FormHandler;


use AppBundle\Entity\User;
use AppBundle\Service\UserService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EditUserFormHandler
{
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;
    /**
     * @var UserService
     */
    private $userService;

    /**
     * CreateTaskFormHandler constructor.
     * @param UserService $userService
     * @param ValidatorInterface $validator
     * @param FlashBagInterface $flashBag
     */
    public function __construct(UserService $userService, ValidatorInterface $validator, FlashBagInterface $flashBag)
    {
        $this->validator = $validator;
        $this->flashBag = $flashBag;
        $this->userService = $userService;
    }

    public function handle(FormInterface $form, User $user)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->updateUser($form->getData(), $user);
            if (count($errors = $this->validator->validate($user))) {
                foreach ($errors as $error) {
                    $this->flashBag->add('error', $error->getMessage());
                }
                return false;
            }
            $this->userService->save($user);
            return true;
        }
        return false;
    }
}