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
 * Interface for type hinting and having a similar interface for custom implementations.
 */
interface BreadcrumbInterface
{
    public function __construct(string $label, string $route, array $routeParameters = [], array $labelParameters = []);

    public function getRoute(): string;

    public function getLabel(): string;

    /**
     * @return $this
     */
    public function setRouteParameters(array $routeParameters): self;

    /**
     * @return $this
     */
    public function setLabelParameters(array $labelParameters): self;

    public function getRouteParameters(): array;

    public function getLabelParameters(): array;
}
