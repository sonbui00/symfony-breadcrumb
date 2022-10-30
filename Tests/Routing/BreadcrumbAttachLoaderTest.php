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

namespace Azri\BreadcrumbBundle\Tests\Routing;

use Azri\BreadcrumbBundle\Routing\BreadcrumbAttachLoader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Tests the router loader that hooks in and attaches the breadcrumb options to _breadcrumb defaults.
 */
class BreadcrumbAttachLoaderTest extends TestCase
{
    /**
     * @var BreadcrumbAttachLoader
     */
    private $loader;

    /**
     * @var MockObject|LoaderResolverInterface
     */
    private $delegatingLoader;

    /**
     * Set up mocks for the whole router loader.
     */
    public function setUp(): void
    {
        /** @var MockObject|LoaderInterface $delegatingLoader */
        $delegatingLoader = $this->getMockBuilder('Symfony\Component\Config\Loader\LoaderInterface')
            ->onlyMethods(['load'])
            ->getMockForAbstractClass();

        $this->delegatingLoader = $delegatingLoader;
        $this->loader = new BreadcrumbAttachLoader($this->delegatingLoader);
    }

    /**
     * Test the loading and set up of multiple breadcrumbs on mutiple routes.
     */
    public function testLoad(): void
    {
        $collection = new RouteCollection();

        $route1Crumbs = [
            'breadcrumb' => [
                'label' => 'Foo',
                'parent_route' => 'bar',
            ],
        ];
        $route2Crumbs = [
            'breadcrumb' => [
                'label' => 'Bar',
            ],
        ];

        $collection->add('foo', new Route('/foo', [], [], $route1Crumbs));
        $collection->add('bar', new Route('/bar', [], [], $route2Crumbs));

        $this->delegatingLoader->expects($this->once())
            ->method('load')
            ->will($this->returnValue($collection));

        /** @var RouteCollection $result */
        $result = $this->loader->load('foobar');

        $this->assertCount(2, $result->all());
        $this->assertCount(2, $result->get('foo')->getDefault('_breadcrumbs'));
        $this->assertEquals([
            ['label' => 'Bar', 'route' => 'bar'],
            ['label' => 'Foo', 'route' => 'foo'],
        ], $result->get('foo')->getDefault('_breadcrumbs'));
        $this->assertEquals(['label' => 'Bar', 'route' => 'bar'], $result->get('foo')->getDefault('_breadcrumbs')[0]);
        $this->assertEquals(['label' => 'Foo', 'route' => 'foo'], $result->get('foo')->getDefault('_breadcrumbs')[1]);

        $this->assertCount(1, $result->get('bar')->getDefault('_breadcrumbs'));
        $this->assertEquals(['label' => 'Bar', 'route' => 'bar'], $result->get('bar')->getDefault('_breadcrumbs')[0]);
    }

    /**
     * Test exception if one breadcrumb is missing its label.
     */
    public function testMalformedBreadcrumb(): void
    {
        $route1Crumbs = [
            'breadcrumb' => [
                // label missing
                'parent_route' => 'bar',
            ],
        ];
        $route2Crumbs = [
            'breadcrumb' => [
                'label' => 'Bar',
            ],
        ];

        $collection = new RouteCollection();
        $collection->add('foo', new Route('/foo', [], [], $route1Crumbs));
        $collection->add('bar', new Route('/bar', [], [], $route2Crumbs));

        $this->delegatingLoader->expects($this->once())
            ->method('load')
            ->will($this->returnValue($collection));

        $this->expectException('\InvalidArgumentException');
        $this->loader->load('foobar');
    }

    /**
     * Test behaviour of loader when breadcrumbs are configured circular (a -> b -> a etc.).
     */
    public function testCircularBreadcrumbs(): void
    {
        $routeFooName = 'foo';
        $routeBarName = 'bar';

        $routeFooCrumbs = [
            'breadcrumb' => [
                'label' => 'Foo',
                'parent_route' => $routeBarName,
            ],
        ];
        $routeBarCrumbs = [
            'breadcrumb' => [
                'label' => 'Bar',
                'parent_route' => $routeFooName,
            ],
        ];

        $collection = new RouteCollection();
        $collection->add($routeFooName, new Route('/foo', [], [], $routeFooCrumbs));
        $collection->add($routeBarName, new Route('/bar', [], [], $routeBarCrumbs));

        $this->delegatingLoader->expects($this->once())
            ->method('load')
            ->will($this->returnValue($collection));

        $this->expectException('\LogicException');
        $this->loader->load('foobar');
    }
}
