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

namespace Azri\BreadcrumbBundle;

use Azri\BreadcrumbBundle\DependencyInjection\AzriBreadcrumbExtension;
use Azri\BreadcrumbBundle\DependencyInjection\RoutingLoaderCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Breadcrumb bundle class.
 *
 * @codeCoverageIgnore
 */
class AzriBreadcrumbBundle extends Bundle
{
    /**
     * @return ?AzriBreadcrumbExtension
     */
    public function getContainerExtension(): ?AzriBreadcrumbExtension
    {
        return new AzriBreadcrumbExtension();
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RoutingLoaderCompilerPass());
    }
}
