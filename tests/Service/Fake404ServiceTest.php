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

    public function testGetRandomErrorPageWithTemplates(): void
    {
        // 准备模拟 twig 渲染结果
        $this->twig->expects($this->once())
            ->method('render')
            ->willReturn('<html><body>404 Error Page</body></html>');

        $response = $this->service->getRandomErrorPage();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertEquals('<html><body>404 Error Page</body></html>', $response->getContent());
    }

    public function testGetRandomErrorPageWithNoTemplates(): void
    {
        // 创建一个带有空模板目录的服务实例
        $emptyDir = sys_get_temp_dir() . '/empty_templates_' . uniqid();
        if (!is_dir($emptyDir)) {
            mkdir($emptyDir);
        }

        $service = new Fake404Service($this->twig, $emptyDir);

        $this->assertNull($service->getRandomErrorPage());

        // 清理测试目录
        rmdir($emptyDir);
    }
}
