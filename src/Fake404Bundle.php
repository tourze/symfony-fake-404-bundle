<?php

namespace Tourze\Fake404Bundle;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;

class Fake404Bundle extends Bundle implements BundleDependencyInterface
{
    public static function getBundleDependencies(): array
    {
        return [
            FrameworkBundle::class => ['all' => true],
            TwigBundle::class => ['all' => true],
        ];
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        // 设置默认参数，确保在测试环境中也能正常工作
        if (!$container->hasParameter('fake404.templates_dir')) {
            $container->setParameter('fake404.templates_dir', __DIR__ . '/Resources/views/pages');
        }
    }
}
