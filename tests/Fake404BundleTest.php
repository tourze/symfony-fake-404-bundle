<?php

namespace Tourze\Fake404Bundle\Tests;

use PHPUnit\Framework\TestCase;
use Tourze\Fake404Bundle\Fake404Bundle;

class Fake404BundleTest extends TestCase
{
    public function testBundleInstantiation(): void
    {
        $bundle = new Fake404Bundle();

        // 验证 bundle 能够被实例化
        $this->assertInstanceOf(Fake404Bundle::class, $bundle);
    }
}
