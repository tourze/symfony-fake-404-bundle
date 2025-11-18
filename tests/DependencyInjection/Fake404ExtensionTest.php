<?php

namespace Tourze\Fake404Bundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\Fake404Bundle\DependencyInjection\Fake404Extension;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;

/**
 * @internal
 */
#[CoversClass(Fake404Extension::class)]
final class Fake404ExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    private Fake404Extension $extension;

    private ContainerBuilder $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extension = new Fake404Extension();
        $this->container = new ContainerBuilder();

        // Set required parameters for AutoExtension
        $this->container->setParameter('kernel.environment', 'test');
        $this->container->setParameter('kernel.debug', true);
        $this->container->setParameter('kernel.cache_dir', sys_get_temp_dir());
        $this->container->setParameter('kernel.logs_dir', sys_get_temp_dir());
        $this->container->setParameter('kernel.project_dir', __DIR__ . '/../../');
    }

    public function testPrependSetsTemplatesDirParameter(): void
    {
        // Act
        $this->extension->prepend($this->container);

        // Assert
        $this->assertTrue($this->container->hasParameter('fake404.templates_dir'));
        $expectedPath = realpath(__DIR__ . '/../../src/DependencyInjection/../Resources/views/pages');
        $parameterValue = $this->container->getParameter('fake404.templates_dir');
        $this->assertIsString($parameterValue);
        $actualPath = realpath($parameterValue);
        $this->assertSame($expectedPath, $actualPath);
    }

    public function testGetConfigDirReturnsCorrectPath(): void
    {
        // Act - Use reflection to access protected method
        $reflection = new \ReflectionClass($this->extension);
        $method = $reflection->getMethod('getConfigDir');
        $method->setAccessible(true);
        $configDir = $method->invoke($this->extension);

        // Assert
        $this->assertIsString($configDir);
        $expectedPath = realpath(__DIR__ . '/../../src/DependencyInjection/../Resources/config');
        $actualPath = realpath($configDir);
        $this->assertSame($expectedPath, $actualPath);
    }
}
