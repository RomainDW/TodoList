<?php


namespace Tests\AppBundle\Listener;

use AppBundle\Listener\ExceptionListener;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Tests\AppBundle\MyTestCase;

class ExceptionListenerTest extends MyTestCase
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
        parent::setUp();

        $this->router = $this->createMock(RouterInterface::class);
        $this->flashBag = $this->createMock(FlashBagInterface::class);
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
