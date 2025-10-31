<?php

namespace Tourze\Fake404Bundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\Fake404Bundle\EventSubscriber\NotFoundExceptionEventSubscriber;
use Tourze\Fake404Bundle\Service\Fake404Service;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;

/**
 * @internal
 */
#[CoversClass(Fake404Service::class)]
#[RunTestsInSeparateProcesses] final class Fake404ServiceIntegrationTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
        // Extension 测试不需要特殊的设置
    }

    public function testServiceIsRegisteredInContainer(): void
    {
        $container = self::getContainer();

        // Assert - 验证模板目录参数是否已设置
        $this->assertTrue($container->hasParameter('fake404.templates_dir'));
        $templatesDir = $container->getParameter('fake404.templates_dir');
        $this->assertIsString($templatesDir);
        $this->assertStringEndsWith('Resources/views/pages', $templatesDir);

        // Assert - 验证服务是否已注册并可获取
        $this->assertTrue($container->has(Fake404Service::class));
        $this->assertTrue($container->has(NotFoundExceptionEventSubscriber::class));

        // 验证服务可以正常获取
        $fake404Service = $container->get(Fake404Service::class);
        $this->assertInstanceOf(Fake404Service::class, $fake404Service);

        $subscriber = $container->get(NotFoundExceptionEventSubscriber::class);
        $this->assertInstanceOf(NotFoundExceptionEventSubscriber::class, $subscriber);
    }
}
