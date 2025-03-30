<?php

namespace Tourze\Fake404Bundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Tourze\Fake404Bundle\Service\Fake404Service;

class NotFoundExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly Fake404Service $fake404Service,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 0],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof NotFoundHttpException) {
            return;
        }

        $response = $this->fake404Service->getRandomErrorPage();
        if ($response === null) {
            return;
        }

        $event->setResponse($response);
    }
}
