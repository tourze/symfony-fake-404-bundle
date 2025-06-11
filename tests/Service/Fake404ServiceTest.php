<?php

namespace Tourze\Fake404Bundle\Tests\Service;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Tourze\Fake404Bundle\Service\Fake404Service;
use Twig\Environment;

class Fake404ServiceTest extends TestCase
{
    private Environment|MockObject $twig;
    private string $templatesDir;
    private Fake404Service $service;

    protected function setUp(): void
    {
        $this->twig = $this->createMock(Environment::class);
        $this->templatesDir = __DIR__ . '/../../src/Resources/views/pages';
        $this->service = new Fake404Service($this->twig, $this->templatesDir);
    }

    public function test_getRandomErrorPage_withAvailableTemplates_returnsValidResponse(): void
    {
        // Arrange
        $expectedContent = '<html><body>404 Error Page</body></html>';
        $this->twig->expects($this->once())
            ->method('render')
            ->willReturn($expectedContent);

        // Act
        $response = $this->service->getRandomErrorPage();

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertEquals($expectedContent, $response->getContent());
    }

    public function test_getRandomErrorPage_withNoTemplatesAvailable_returnsNull(): void
    {
        // Arrange
        $emptyDir = sys_get_temp_dir() . '/empty_templates_' . uniqid();
        if (!is_dir($emptyDir)) {
            mkdir($emptyDir);
        }
        $service = new Fake404Service($this->twig, $emptyDir);

        // Act
        $result = $service->getRandomErrorPage();

        // Assert
        $this->assertNull($result);

        // Cleanup
        rmdir($emptyDir);
    }

    public function test_getRandomErrorPage_withTwigRenderException_throwsException(): void
    {
        // Arrange
        $this->twig->expects($this->once())
            ->method('render')
            ->willThrowException(new \Exception('Twig render error'));

        // Assert & Act
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Twig render error');
        $this->service->getRandomErrorPage();
    }

    public function test_constructor_loadsTemplatesFromDirectory(): void
    {
        // Arrange
        $mockTwig = $this->createMock(Environment::class);
        $templatesDir = __DIR__ . '/../../src/Resources/views/pages';

        // Act
        $service = new Fake404Service($mockTwig, $templatesDir);

        // Assert - 通过调用方法验证模板已加载
        $mockTwig->expects($this->once())
            ->method('render')
            ->willReturn('test content');

        $response = $service->getRandomErrorPage();
        $this->assertNotNull($response);
    }
}
