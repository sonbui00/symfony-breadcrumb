<?php

/*
 * This file is part of the Symfony Azri Breadcrumb Bundle.
 *
 * @author Bilel Azri    <azri.bilel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 *
 * Azri Breadcrumb Bundle (c) 2023.
 */

declare(strict_types=1);

namespace Azri\BreadcrumbBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class RoutingLoaderCompilerPass.
 */
class RoutingLoaderCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container): void
    {
        $routingLoaderDefinition = $container->getDefinition('routing.loader');

        $container->setDefinition('azri_breadcrumb.routing.attach_breadcrumb_loader.inner', $routingLoaderDefinition);

        $container->setAlias('routing.loader', 'azri_breadcrumb.routing.attach_breadcrumb_loader')->setPublic(true);
    }
}
