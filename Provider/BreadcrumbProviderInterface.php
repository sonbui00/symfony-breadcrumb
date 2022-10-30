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

namespace Azri\BreadcrumbBundle\Provider;

use Azri\BreadcrumbBundle\Model\BreadcrumbCollectionInterface;

/**
 * Interface BreadcrumbProviderInterface.
 */
interface BreadcrumbProviderInterface
{
    /**
     * Get the BreadcrumbCollection for the current requests route.
     *
     * @return ?BreadcrumbCollectionInterface
     */
    public function getBreadcrumbs(): ?BreadcrumbCollectionInterface;
}
