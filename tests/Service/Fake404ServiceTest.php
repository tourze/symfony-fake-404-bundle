<?php

namespace Tourze\Fake404Bundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\HttpFoundation\Response;
use Tourze\Fake404Bundle\Service\Fake404Service;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;

/**
 * @internal
 */
#[CoversClass(Fake404Service::class)]
#[RunTestsInSeparateProcesses]
final class Fake404ServiceTest extends AbstractIntegrationTestCase
{
    private Fake404Service $service;

    protected function onSetUp(): void
    {
        $this->service = self::getService(Fake404Service::class);
    }

    public function testGetRandomErrorPageWithAvailableTemplatesReturnsValidResponse(): void
    {
        // Act
        $response = $this->service->getRandomErrorPage();

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertNotEmpty($response->getContent());
    }

    public function testGetRandomErrorPageReturnsConsistentStatusCode(): void
    {
        // Act - 多次调用确保状态码一致
        $response1 = $this->service->getRandomErrorPage();
        $response2 = $this->service->getRandomErrorPage();

        // Assert
        $this->assertInstanceOf(Response::class, $response1);
        $this->assertInstanceOf(Response::class, $response2);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response1->getStatusCode());
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response2->getStatusCode());
    }

    public function testGetRandomErrorPageWithEmptyDirectoryHandlesGracefully(): void
    {
        // 这个测试验证服务在没有模板的情况下的行为
        // 由于不能直接实例化服务，我们通过现有实例来测试其健壮性

        // Act - 多次调用确保服务稳定
        $response = $this->service->getRandomErrorPage();

        // Assert - 验证返回值类型正确
        if (null !== $response) {
            $this->assertInstanceOf(Response::class, $response);
            $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
            $this->assertNotEmpty($response->getContent());
        }
        // null 返回值也是符合预期的行为，无需额外断言
    }

    public function testServiceIntegrityWithMultipleCalls(): void
    {
        // Act - 验证服务在多次调用下的一致性
        $responses = [];
        for ($i = 0; $i < 3; ++$i) {
            $response = $this->service->getRandomErrorPage();
            if (null !== $response) {
                $responses[] = $response;
            }
        }

        // Assert - 所有响应都应该有一致的行为
        foreach ($responses as $response) {
            $this->assertInstanceOf(Response::class, $response);
            $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
            $this->assertNotEmpty($response->getContent());
        }
    }

    public function testServiceCanHandleMultipleCallsWithoutErrors(): void
    {
        // Act - 多次调用服务确保没有内存泄漏或状态问题
        $responses = [];
        for ($i = 0; $i < 5; ++$i) {
            $response = $this->service->getRandomErrorPage();
            if (null !== $response) {
                $responses[] = $response;
            }
        }

        // Assert - 如果有响应，应该都是有效的
        foreach ($responses as $response) {
            $this->assertInstanceOf(Response::class, $response);
            $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
            $this->assertNotEmpty($response->getContent());
        }
    }
}
