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
 * Breadcrumb collection that holds all breadcrumbs and allows special operations on it.
 */
class BreadcrumbCollection implements BreadcrumbCollectionInterface
{
    /**
     * @var BreadcrumbInterface[] Array of breadcrumbs
     */
    private array $breadcrumbs = [];

    /**
     * @return $this
     */
    public function addBreadcrumb(BreadcrumbInterface $breadcrumb): self
    {
        $this->breadcrumbs[] = $breadcrumb;

        return $this;
    }

    /**
     * @return $this
     */
    public function addBreadcrumbBeforeCrumb(BreadcrumbInterface $newBreadcrumb, BreadcrumbInterface $positionBreadcrumb): self
    {
        return $this->addBreadcrumbAtPosition($newBreadcrumb, ($this->getBreadcrumbPosition($positionBreadcrumb)));
    }

    /**
     * @return $this
     */
    public function addBreadcrumbAfterCrumb(BreadcrumbInterface $newBreadcrumb, BreadcrumbInterface $positionBreadcrumb): self
    {
        return $this->addBreadcrumbAtPosition($newBreadcrumb, ($this->getBreadcrumbPosition($positionBreadcrumb) + 1));
    }

    /**
     * If $position is positive then the start of removed
     * portion is at that offset from the beginning of the
     * breadcrumbs. If $position is negative then it starts that
     * far from the end of the breadcrumbs.
     *
     * @return $this
     */
    public function addBreadcrumbAtPosition(BreadcrumbInterface $breadcrumb, int $position): self
    {
        array_splice($this->breadcrumbs, $position, 0, [$breadcrumb]);

        return $this;
    }

    /**
     * @return $this
     */
    public function addBreadcrumbToStart(BreadcrumbInterface $breadcrumb): self
    {
        array_unshift($this->breadcrumbs, $breadcrumb);

        return $this;
    }

    /**
     * @return BreadcrumbInterface[]
     */
    public function getAll(): array
    {
        return $this->breadcrumbs;
    }

    /**
     * Get the first breadcrumb entry for $route from the breadcrumb tree for the current route.
     */
    public function getBreadcrumbByRoute(string $route): ?BreadcrumbInterface
    {
        foreach ($this->breadcrumbs as $breadcrumb) {
            if ($breadcrumb->getRoute() === $route) {
                return $breadcrumb;
            }
        }

        return null;
    }

    private function getBreadcrumbPosition(BreadcrumbInterface $breadcrumb): int|string
    {
        $position = array_search($breadcrumb, $this->breadcrumbs, true);

        if (false === $position) {
            throw new \InvalidArgumentException(sprintf('Breadcrumb for route "%s" with label "%s" not found', $breadcrumb->getRoute(), $breadcrumb->getLabel()));
        }

        return $position;
    }
}
