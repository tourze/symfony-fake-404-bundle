<?php

declare(strict_types=1);

namespace Tourze\Fake404Bundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\Fake404Bundle\Fake404Bundle;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;

/**
 * @internal
 */
#[CoversClass(Fake404Bundle::class)]
#[RunTestsInSeparateProcesses]
final class Fake404BundleTest extends AbstractBundleTestCase
{
}
