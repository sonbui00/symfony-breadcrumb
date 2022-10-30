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

namespace Azri\BreadcrumbBundle\Tests\Provider;

use Azri\BreadcrumbBundle\Provider\BreadcrumbProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Provider class test.
 */
class BreadcrumbProviderTest extends TestCase
{
    public const MODEL_CLASS = 'Azri\BreadcrumbBundle\Model\Breadcrumb';

    public const COLLECTION_CLASS = 'Azri\BreadcrumbBundle\Model\BreadcrumbCollection';

    /**
     * @var RequestEvent
     */
    private $responseEvent;

    /**
     * @var MockObject|ParameterBag
     */
    private $requestAttributes;

    /**
     * @var BreadcrumbProvider
     */
    private $provider;

    /**
     * Set up the whole.
     */
    public function setUp(): void
    {
        $this->requestAttributes = $this->getMockBuilder('Symfony\Component\HttpFoundation\ParameterBag')
            ->disableOriginalConstructor()
            ->onlyMethods(['get'])
            ->getMock();

        $request = $this->getMockBuilder('\Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();
        $request->attributes = $this->requestAttributes;

        $this->responseEvent = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\RequestEvent')
            ->disableOriginalConstructor()
            ->onlyMethods(['getRequestType', 'getRequest'])
            ->getMock();
        $this->responseEvent->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request));
        $this->responseEvent->expects($this->any())
            ->method('getRequestType')
            ->will($this->returnValue(HttpKernelInterface::MAIN_REQUEST));

        $this->provider = new BreadcrumbProvider(self::MODEL_CLASS, self::COLLECTION_CLASS);
    }

    /**
     * Tests the outcome if there are no configured breadcrumbs.
     */
    public function testGetNoConfiguredBreadcrumbs(): void
    {
        $this->requestAttributes->expects($this->any())
            ->method('get')
            ->will($this->returnValue([]));

        $this->provider->onKernelRequest($this->responseEvent);
        $result = $this->provider->getBreadcrumbs();

        $this->assertInstanceOf('\Azri\BreadcrumbBundle\Model\BreadcrumbCollection', $result);
        $this->assertEmpty($result->getAll());
    }

    /**
     * Test the generation of a single breadcrumb.
     */
    public function testSingleBreadcrumb(): void
    {
        $label = 'foo';
        $route = 'bar';

        $this->requestAttributes->expects($this->any())
            ->method('get')
            ->will($this->returnValue([
                [
                    'label' => $label,
                    'route' => $route,
                ],
            ]));

        $this->provider->onKernelRequest($this->responseEvent);
        $result = $this->provider->getBreadcrumbs();

        $this->assertCount(1, $result->getAll());

        $this->assertEquals($label, $result->getAll()[0]->getLabel());
        $this->assertEquals($route, $result->getAll()[0]->getRoute());
    }

    /**
     * Test the generation of multiple breadcrumbs.
     */
    public function testMultipleBreadcrumbs(): void
    {
        $label1 = 'foo';
        $route1 = 'bar';
        $label2 = 'baz';
        $route2 = 'qux';

        $this->requestAttributes->expects($this->any())
            ->method('get')
            ->will($this->returnValue([
                [
                    'label' => $label1,
                    'route' => $route1,
                ],
                [
                    'label' => $label2,
                    'route' => $route2,
                ],
            ]));

        $this->provider->onKernelRequest($this->responseEvent);
        $result = $this->provider->getBreadcrumbs();

        $this->assertCount(2, $result->getAll());

        $this->assertEquals($label1, $result->getAll()[0]->getLabel());
        $this->assertEquals($route1, $result->getAll()[0]->getRoute());

        $this->assertEquals($label2, $result->getAll()[1]->getLabel());
        $this->assertEquals($route2, $result->getAll()[1]->getRoute());
    }
}
