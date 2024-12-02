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

namespace Azri\BreadcrumbBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Routing\Route;

/**
 * Attaches breadcrumb tree to every routes default config.
 */
class BreadcrumbAttachLoader extends Loader
{
    private LoaderInterface $routerLoader;

    /**
     * Attaches breadcrumb tree to every routes default config.
     */
    public function __construct(LoaderInterface $routerLoader, string $env = null)
    {
        parent::__construct($env);

        $this->routerLoader = $routerLoader;
    }

    /**
     * {@inheritdoc}
     */
    public function load(mixed $resource, $type = null)
    {
        $routeCollection = $this->routerLoader->load($resource, $type);

        foreach ($routeCollection->all() as $key => $route) {
            if ($route->hasOption('breadcrumb')) {
                $route->setDefault(
                    '_breadcrumbs',
                    $this->getBreadcrumb($route, $key)
                );
            }
        }

        return $routeCollection;
    }

    /**
     * Returns whether this class supports the given resource.
     *
     * @param mixed $resource A resource
     * @param null  $type     The resource type or null if unknown
     *
     * @return bool True if this class supports the given resource, false otherwise
     */
    public function supports(mixed $resource, $type = null): bool
    {
        return $this->routerLoader->supports($resource, $type);
    }

    /**
     * Get breadcrumb for the given route.
     */
    private function getBreadcrumb(Route $route, string $routeKey): array
    {
        $breadcrumbOptions = $route->getOption('breadcrumb');

        // No label, no crumb.
        if (false === isset($breadcrumbOptions['label'])) {
            throw new \InvalidArgumentException(sprintf('Label for breadcrumb on route "%s" must be configured', $routeKey));
        }

        $rawCrumb = [
            'route' => $routeKey,
            'label' => $breadcrumbOptions['label'],
        ];

        if (isset($breadcrumbOptions['parent_route'])) {
            $rawCrumb['parent_route'] = $breadcrumbOptions['parent_route'];
        }

        return $rawCrumb;
    }
}
