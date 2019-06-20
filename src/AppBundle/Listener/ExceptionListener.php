<?php


namespace AppBundle\Listener;

use AppBundle\Exception\TaskNotFoundException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Routing\RouterInterface;

class ExceptionListener
{
    /**
     * @var FlashBagInterface
     */
    private $flashBag;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * ExceptionListener constructor.
     * @param FlashBagInterface $flashBag
     * @param RouterInterface $router
     */
    public function __construct(FlashBagInterface $flashBag, RouterInterface $router)
    {
        $this->flashBag = $flashBag;
        $this->router = $router;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if ($exception instanceof TaskNotFoundException) {
            $this->flashBag->add('error', $exception->getMessage());

            $url = $this->router->generate('task_list');
            $response =  new RedirectResponse($url);

            $event->setResponse($response);
        }
    }
}