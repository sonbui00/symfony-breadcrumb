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
 * Class BreadcrumbCollectionInterface.
 */
interface BreadcrumbCollectionInterface
{
    /**
     * @return $this
     */
    public function addBreadcrumb(BreadcrumbInterface $breadcrumb): self;

    /**
     * @return $this
     */
    public function addBreadcrumbBeforeCrumb(BreadcrumbInterface $newBreadcrumb, BreadcrumbInterface $positionBreadcrumb): self;

    /**
     * @return $this
     */
    public function addBreadcrumbAfterCrumb(BreadcrumbInterface $newBreadcrumb, BreadcrumbInterface $positionBreadcrumb): self;

    /**
     * @return $this
     */
    public function addBreadcrumbAtPosition(BreadcrumbInterface $breadcrumb, int $position): self;

    /**
     * @return $this
     */
    public function addBreadcrumbToStart(BreadcrumbInterface $breadcrumb): self;

    public function getAll(): array;

    public function getBreadcrumbByRoute(string $route): ?BreadcrumbInterface;
}
