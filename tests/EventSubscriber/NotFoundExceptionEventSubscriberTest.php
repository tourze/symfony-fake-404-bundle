<?php

namespace Tourze\Fake404Bundle\Tests\EventSubscriber;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Tourze\Fake404Bundle\EventSubscriber\NotFoundExceptionEventSubscriber;
use Tourze\Fake404Bundle\Service\Fake404Service;
use Tourze\PHPUnitSymfonyKernelTest\AbstractEventSubscriberTestCase;

/**
 * @internal
 */
#[CoversClass(NotFoundExceptionEventSubscriber::class)]
#[RunTestsInSeparateProcesses]
final class NotFoundExceptionEventSubscriberTest extends AbstractEventSubscriberTestCase
{
    private Fake404Service $fake404Service;

    private ?NotFoundExceptionEventSubscriber $subscriber = null;

    private Request $request;

    protected function onSetUp(): void
    {
        $this->request = new Request();
    }

    public function testGetSubscribedEventsReturnsCorrectEventConfiguration(): void
    {
        // Arrange

        // Act
        $events = NotFoundExceptionEventSubscriber::getSubscribedEvents();

        // Assert
        $this->assertArrayHasKey(KernelEvents::EXCEPTION, $events);
        $this->assertEquals(['onKernelException', 0], $events[KernelEvents::EXCEPTION]);
    }

    public function testOnKernelExceptionWithNotFoundHttpExceptionSetsCustomResponse(): void
    {
        // Arrange
        $kernel = new class implements HttpKernelInterface {
            public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
            {
                return new Response();
            }
        };
        $exception = new NotFoundHttpException('Not Found');
        $event = new ExceptionEvent(
            $kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            $exception
        );
        $response = new Response('404 error page', Response::HTTP_NOT_FOUND);

        // 创建 Mock 服务并设置到容器中
        $this->fake404Service = $this->createMock(Fake404Service::class);
        $this->fake404Service
            ->method('getRandomErrorPage')
            ->willReturn($response);
        self::getContainer()->set(Fake404Service::class, $this->fake404Service);

        // Act
        $this->subscriber = self::getService(NotFoundExceptionEventSubscriber::class);
        $this->subscriber->onKernelException($event);

        // Assert
        $this->assertSame($response, $event->getResponse());
    }

    public function testOnKernelExceptionWithOtherExceptionDoesNotSetResponse(): void
    {
        // Arrange
        $kernel = new class implements HttpKernelInterface {
            public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
            {
                return new Response();
            }
        };
        $exception = new \Exception('Other exception');
        $event = new ExceptionEvent(
            $kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            $exception
        );

        // 创建 Mock 服务并设置到容器中
        $this->fake404Service = $this->createMock(Fake404Service::class);
        $this->fake404Service
            ->method('getRandomErrorPage')
            ->willReturn(null);
        self::getContainer()->set(Fake404Service::class, $this->fake404Service);

        // Act
        $this->subscriber = self::getService(NotFoundExceptionEventSubscriber::class);
        $this->subscriber->onKernelException($event);

        // Assert
        $this->assertNull($event->getResponse());
    }

    public function testOnKernelExceptionWithNoResponseFromServiceDoesNotSetResponse(): void
    {
        // Arrange
        $kernel = new class implements HttpKernelInterface {
            public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
            {
                return new Response();
            }
        };
        $exception = new NotFoundHttpException('Not Found');
        $event = new ExceptionEvent(
            $kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            $exception
        );

        // 创建 Mock 服务并设置到容器中
        $this->fake404Service = $this->createMock(Fake404Service::class);
        $this->fake404Service
            ->method('getRandomErrorPage')
            ->willReturn(null);
        self::getContainer()->set(Fake404Service::class, $this->fake404Service);

        // Act
        $this->subscriber = self::getService(NotFoundExceptionEventSubscriber::class);
        $this->subscriber->onKernelException($event);

        // Assert
        $this->assertNull($event->getResponse());
    }
}
