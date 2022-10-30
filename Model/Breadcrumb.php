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

namespace Azri\BreadcrumbBundle\Model;

/**
 * Single breadcrumb model.
 */
class Breadcrumb implements BreadcrumbInterface
{
    private string $label;

    private string $route;

    private array $routeParameters;

    private array $labelParameters;

    public function __construct(string $label, string $route, array $routeParameters = [], array $labelParameters = [])
    {
        $this->label = $label;
        $this->route = $route;
        $this->setRouteParameters($routeParameters);
        $this->setLabelParameters($labelParameters);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @codeCoverageIgnore
     *
     * @return $this
     */
    public function setRouteParameters(array $routeParameters): self
    {
        $this->routeParameters = $routeParameters;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     *
     * @return $this
     */
    public function setLabelParameters(array $labelParameters): self
    {
        $this->labelParameters = $labelParameters;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRouteParameters(): array
    {
        return $this->routeParameters;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getLabelParameters(): array
    {
        return $this->labelParameters;
    }
}
