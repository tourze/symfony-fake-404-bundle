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

    public function testGetSubscribedEvents(): void
    {
        $events = NotFoundExceptionSubscriber::getSubscribedEvents();

        $this->assertArrayHasKey(KernelEvents::EXCEPTION, $events);
        $this->assertEquals(['onKernelException', 0], $events[KernelEvents::EXCEPTION]);
    }

    public function testOnKernelExceptionWithNotFoundHttpException(): void
    {
        // 创建实际的 NotFoundHttpException 异常
        $exception = new NotFoundHttpException('Not Found');

        // 创建实际的 ExceptionEvent 对象
        $event = new ExceptionEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            $exception
        );

        // 模拟服务返回响应
        $response = new Response('404 error page', Response::HTTP_NOT_FOUND);
        $this->fake404Service->expects($this->once())
            ->method('getRandomErrorPage')
            ->willReturn($response);

        $this->subscriber->onKernelException($event);

        // 验证是否已设置响应
        $this->assertSame($response, $event->getResponse());
    }

    public function testOnKernelExceptionWithOtherException(): void
    {
        // 创建非 NotFoundHttpException 异常
        $exception = new \Exception('Other exception');

        // 创建实际的 ExceptionEvent 对象
        $event = new ExceptionEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            $exception
        );

        // 确认不会调用服务
        $this->fake404Service->expects($this->never())
            ->method('getRandomErrorPage');

        $this->subscriber->onKernelException($event);

        // 验证未设置响应
        $this->assertNull($event->getResponse());
    }

    public function testOnKernelExceptionWithNoResponseFromService(): void
    {
        // 创建 NotFoundHttpException 异常
        $exception = new NotFoundHttpException('Not Found');

        // 创建实际的 ExceptionEvent 对象
        $event = new ExceptionEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            $exception
        );

        // 模拟服务不返回响应
        $this->fake404Service->expects($this->once())
            ->method('getRandomErrorPage')
            ->willReturn(null);

        $this->subscriber->onKernelException($event);

        // 验证未设置响应
        $this->assertNull($event->getResponse());
    }
}
