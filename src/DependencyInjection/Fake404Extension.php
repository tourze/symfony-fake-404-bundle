<?php

namespace Tourze\Fake404Bundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\SymfonyDependencyServiceLoader\AutoExtension;

class Fake404Extension extends AutoExtension
{
    protected function getConfigDir(): string
    {
        return __DIR__ . '/../Resources/config';
    }

    public function prepend(ContainerBuilder $container): void
    {
        $container->setParameter('fake404.templates_dir', __DIR__ . '/../Resources/views/pages');
    }
}
