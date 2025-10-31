<?php

namespace Tourze\Fake404Bundle\EventSubscriber;

use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Tourze\Fake404Bundle\Service\Fake404Service;

#[WithMonologChannel(channel: 'fake_404')]
readonly class NotFoundExceptionEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Fake404Service $fake404Service,
        private LoggerInterface $logger,
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

        if (filter_var($_ENV['FAKE_404_LOG_REQUEST'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            $this->logger->info('捕捉到404异常', [
                'exception' => $exception,
                'requestHeaders' => $event->getRequest()->headers->all(),
                'requestContent' => $event->getRequest()->getContent(),
                'requestUri' => $event->getRequest()->getUri(),
                'requestMethod' => $event->getRequest()->getMethod(),
            ]);
        }

        $response = $this->fake404Service->getRandomErrorPage();
        if (null === $response) {
            return;
        }

        $event->setResponse($response);
    }
}
