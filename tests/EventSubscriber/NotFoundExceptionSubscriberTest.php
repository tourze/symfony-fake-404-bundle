<?php

namespace Tourze\Fake404Bundle\Tests\EventSubscriber;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;
use Tourze\Fake404Bundle\EventSubscriber\NotFoundExceptionSubscriber;
use Tourze\Fake404Bundle\Service\Fake404Service;

class NotFoundExceptionSubscriberTest extends TestCase
{
    private Fake404Service|MockObject $fake404Service;
    private NotFoundExceptionSubscriber $subscriber;
    private KernelInterface|MockObject $kernel;
    private Request $request;

    protected function setUp(): void
    {
        $this->fake404Service = $this->createMock(Fake404Service::class);
        $this->subscriber = new NotFoundExceptionSubscriber($this->fake404Service);
        $this->kernel = $this->createMock(KernelInterface::class);
        $this->request = new Request();
    }

    public function test_getSubscribedEvents_returnsCorrectEventConfiguration(): void
    {
        // Act
        $events = NotFoundExceptionSubscriber::getSubscribedEvents();

        // Assert
        $this->assertArrayHasKey(KernelEvents::EXCEPTION, $events);
        $this->assertEquals(['onKernelException', 0], $events[KernelEvents::EXCEPTION]);
    }

    public function test_onKernelException_withNotFoundHttpException_setsCustomResponse(): void
    {
        // Arrange
        $exception = new NotFoundHttpException('Not Found');
        $event = new ExceptionEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            $exception
        );
        $response = new Response('404 error page', Response::HTTP_NOT_FOUND);
        $this->fake404Service->expects($this->once())
            ->method('getRandomErrorPage')
            ->willReturn($response);

        // Act
        $this->subscriber->onKernelException($event);

        // Assert
        $this->assertSame($response, $event->getResponse());
    }

    public function test_onKernelException_withOtherException_doesNotSetResponse(): void
    {
        // Arrange
        $exception = new \Exception('Other exception');
        $event = new ExceptionEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            $exception
        );
        $this->fake404Service->expects($this->never())
            ->method('getRandomErrorPage');

        // Act
        $this->subscriber->onKernelException($event);

        // Assert
        $this->assertNull($event->getResponse());
    }

    public function test_onKernelException_withNoResponseFromService_doesNotSetResponse(): void
    {
        // Arrange
        $exception = new NotFoundHttpException('Not Found');
        $event = new ExceptionEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            $exception
        );
        $this->fake404Service->expects($this->once())
            ->method('getRandomErrorPage')
            ->willReturn(null);

        // Act
        $this->subscriber->onKernelException($event);

        // Assert
        $this->assertNull($event->getResponse());
    }
}
