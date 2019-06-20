<?php


namespace AppBundle\FormHandler;


use AppBundle\Service\UserService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUserFormHandler
{
    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * CreateUserFormHandler constructor.
     * @param UserService $userService
     * @param ValidatorInterface $validator
     * @param FlashBagInterface $flashBag
     */
    public function __construct(UserService $userService, ValidatorInterface $validator, FlashBagInterface $flashBag)
    {
        $this->userService = $userService;
        $this->validator = $validator;
        $this->flashBag = $flashBag;
    }

    public function handle(FormInterface $form)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->userService->initUser($form->getData());
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