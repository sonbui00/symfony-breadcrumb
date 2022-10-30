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

namespace Azri\BreadcrumbBundle\Twig;

use Azri\BreadcrumbBundle\Provider\BreadcrumbProviderInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Twig extension for breadcrumbs: Render a given template.
 */
class BreadcrumbExtension extends AbstractExtension
{
    private BreadcrumbProviderInterface $breadcrumbProvider;

    private string $template;

    public function __construct(BreadcrumbProviderInterface $breadcrumbProvider, string $template)
    {
        $this->breadcrumbProvider = $breadcrumbProvider;
        $this->template = $template;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'breadcrumbs',
                [
                    $this,
                    'renderBreadcrumbs',
                ],
                [
                    'needs_environment' => true,
                    'is_safe' => ['html'],
                ],
            ),
        ];
    }

    public function renderBreadcrumbs(Environment $twigEnvironment): string
    {
        return $twigEnvironment->render($this->template, [
            'breadcrumbs' => $this->breadcrumbProvider->getBreadcrumbs()->getAll(),
        ]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getName(): string
    {
        return 'azri.breadcrumb_bundle.twig_extension';
    }
}
