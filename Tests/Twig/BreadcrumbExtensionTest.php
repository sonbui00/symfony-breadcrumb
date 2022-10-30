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

namespace Azri\BreadcrumbBundle\Tests\Twig;

use Azri\BreadcrumbBundle\Model\BreadcrumbCollection;
use Azri\BreadcrumbBundle\Provider\BreadcrumbProvider;
use Azri\BreadcrumbBundle\Twig\BreadcrumbExtension;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Test for twig extension.
 */
class BreadcrumbExtensionTest extends TestCase
{
    /**
     * @var string dummy template name
     */
    private $template = 'foo';

    /**
     * @var array Dummy crumb data
     */
    private $crumbs = [];

    /**
     * @var string Dummy string that functions as rendered template
     */
    private $renderedTemplate = 'bar';

    /**
     * Test rendering call of breadcrumb extension.
     */
    public function testRenderBreadcrumbs(): void
    {
        $twigEnv = $this->getMockBuilder('Twig\Environment')
            ->disableOriginalConstructor()
            ->getMock();

        $twigEnv->expects($this->once())
            ->method('render')
            ->will($this->returnCallback([$this, 'renderCallback']));

        /** @var MockObject|BreadcrumbProvider $provider */
        $provider = $this->getMockBuilder('\Azri\BreadcrumbBundle\Provider\BreadcrumbProvider')
            ->disableOriginalConstructor()
            ->getMock();

        $provider->expects($this->once())
            ->method('getBreadcrumbs')
            ->will($this->returnValue(new BreadcrumbCollection()));

        $extension = new BreadcrumbExtension($provider, $this->template);

        $this->assertEquals($this->renderedTemplate, $extension->renderBreadcrumbs($twigEnv));
    }

    /**
     * Callback of twigEnv->render.
     *
     * @param string $template
     *
     * @return string
     */
    public function renderCallback($template, array $templateArgs)
    {
        $this->assertEquals($this->template, $template);
        $this->assertArrayHasKey('breadcrumbs', $templateArgs);
        $this->assertEquals($this->crumbs, $templateArgs['breadcrumbs']);

        return $this->renderedTemplate;
    }
}
