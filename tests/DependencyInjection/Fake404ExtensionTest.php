<?php

namespace Tourze\Fake404Bundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\Fake404Bundle\DependencyInjection\Fake404Extension;
use Tourze\Fake404Bundle\EventSubscriber\NotFoundExceptionSubscriber;
use Tourze\Fake404Bundle\Service\Fake404Service;

class Fake404ExtensionTest extends TestCase
{
    private Fake404Extension $extension;
    private ContainerBuilder $container;

    protected function setUp(): void
    {
        $this->extension = new Fake404Extension();
        $this->container = new ContainerBuilder();
    }

    public function testLoad(): void
    {
        $this->extension->load([], $this->container);

        // 验证模板目录参数是否已设置
        $this->assertTrue($this->container->hasParameter('fake404.templates_dir'));
        $templatesDir = $this->container->getParameter('fake404.templates_dir');
        $this->assertStringEndsWith('Resources/views/pages', $templatesDir);

        // 验证服务定义是否已注册
        $this->assertTrue($this->container->hasDefinition(Fake404Service::class));
        $this->assertTrue($this->container->hasDefinition(NotFoundExceptionSubscriber::class));
    }
}
