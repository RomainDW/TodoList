<?php


namespace Tests\AppBundle\FormHandler;

use AppBundle\FormHandler\CreateUserFormHandler;
use AppBundle\FormHandler\EditUserFormHandler;
use AppBundle\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EditUserFormHandlerTest extends KernelTestCase
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

    public function setUp()
    {
        $this->bootKernel();

        $this->userService = $this->createMock(UserService::class);
        $this->validator = static::$kernel->getContainer()->get('validator');
        $this->flashBag = static::$kernel->getContainer()->get('session.flash_bag');
    }

    public function testDependencies()
    {
        $this->assertInstanceOf(UserService::class, $this->userService);
        $this->assertInstanceOf(ValidatorInterface::class, $this->validator);
        $this->assertInstanceOf(FlashBagInterface::class, $this->flashBag);

        $createTaskFormHandler = new EditUserFormHandler($this->userService, $this->validator, $this->flashBag);
        $this->assertInstanceOf(
            EditUserFormHandler::class,
            $createTaskFormHandler
        );
    }
}
