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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * DI configuration.
 *
 * @codeCoverageIgnore
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('azri_breadcrumb');
        $rootNode = method_exists($treeBuilder, 'getRootNode')
            ? $treeBuilder->getRootNode() : $treeBuilder->root('azri_breadcrumb');

        $rootNode
            ->children()
            ->scalarNode('template')->defaultValue('@AzriBreadcrumb/breadcrumbs.html.twig')->end()
            ->scalarNode('model_class')->defaultValue('Azri\BreadcrumbBundle\Model\Breadcrumb')->end()
            ->scalarNode('collection_class')->defaultValue('Azri\BreadcrumbBundle\Model\BreadcrumbCollection')->end()
            ->scalarNode('provider_service_id')->defaultValue('azri_breadcrumb.breadcrumb_provider.default')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
