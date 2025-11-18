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
            $request = $event->getRequest();
            $content = $request->getContent();
            $envMaxLength = $_ENV['FAKE_404_MAX_CONTENT_LENGTH'] ?? '1024';
            $maxContentLength = is_numeric($envMaxLength) ? (int) $envMaxLength : 1024;

            // 截断过长的请求内容
            $truncatedContent = mb_strlen($content) > $maxContentLength
                ? mb_substr($content, 0, $maxContentLength) . '...[truncated]'
                : $content;

            $this->logger->info('捕捉到404异常', [
                'exception' => $exception,
                'requestHeaders' => $request->headers->all(),
                'requestContent' => $truncatedContent,
                'requestContentLength' => mb_strlen($content),
                'requestUri' => $request->getUri(),
                'requestMethod' => $request->getMethod(),
            ]);
        }

        $response = $this->fake404Service->getRandomErrorPage();
        if (null === $response) {
            return;
        }

        $event->setResponse($response);
    }
}
