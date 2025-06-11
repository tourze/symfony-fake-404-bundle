<?php

namespace Tourze\Fake404Bundle\Tests;

use PHPUnit\Framework\TestCase;
use Tourze\Fake404Bundle\Fake404Bundle;

class Fake404BundleTest extends TestCase
{
    public function test_bundle_canBeInstantiated(): void
    {
        // Act
        $bundle = new Fake404Bundle();

        // Assert
        $this->assertInstanceOf(Fake404Bundle::class, $bundle);
    }

    public function test_getPath_returnsCorrectPath(): void
    {
        // Arrange
        $bundle = new Fake404Bundle();
        $expectedPath = dirname(__DIR__) . '/src';

        // Act
        $actualPath = $bundle->getPath();

        // Assert
        $this->assertEquals($expectedPath, $actualPath);
    }
}
