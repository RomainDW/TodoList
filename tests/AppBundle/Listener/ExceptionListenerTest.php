<?php


namespace Tests\AppBundle\Listener;

use AppBundle\Listener\ExceptionListener;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

class ExceptionListenerTest extends KernelTestCase
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function setUp()
    {
        $this->bootKernel();

        $this->router = static::$kernel->getContainer()->get('router');
        $this->flashBag = static::$kernel->getContainer()->get('session.flash_bag');
    }

    public function testDependencies()
    {
        $this->assertInstanceOf(RouterInterface::class, $this->router);
        $this->assertInstanceOf(FlashBagInterface::class, $this->flashBag);

        $exceptionListener = new ExceptionListener($this->flashBag, $this->router);
        $this->assertInstanceOf(
            ExceptionListener::class,
            $exceptionListener
        );
    }
}
