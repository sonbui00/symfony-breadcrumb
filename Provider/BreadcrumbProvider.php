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
use Azri\BreadcrumbBundle\Model\BreadcrumbInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Breadcrumb factory class that is used to generate and alter breadcrumbs and inject them where needed.
 */
class BreadcrumbProvider implements BreadcrumbProviderInterface
{
    private array $requestBreadcrumbConfig = [];

    private ?BreadcrumbCollectionInterface $breadcrumbs = null;

    private string $modelClass;

    private string $collectionClass;

    private RouterInterface $router;

    public function __construct(string $modelClass, string $collectionClass, RouterInterface $router)
    {
        $this->modelClass = $modelClass;
        $this->collectionClass = $collectionClass;
        $this->router = $router;
    }

    /**
     * Listen to the kernelRequest event to get the breadcrumb config from the request.
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if ($event->getRequestType() === HttpKernelInterface::MAIN_REQUEST) {
            $this->requestBreadcrumbConfig = $event->getRequest()->attributes->get('_breadcrumbs', []);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBreadcrumbs(): ?BreadcrumbCollectionInterface
    {
        if (null === $this->breadcrumbs) {
            $this->breadcrumbs = $this->generateBreadcrumbCollectionFromRequest();
        }

        return $this->breadcrumbs;
    }

    /**
     * Convenience method to get an entry from the breadcrumb list of the current requests route.
     *
     * @see BreadcrumbCollection::getBreadcrumbByRoute
     */
    public function getBreadcrumbByRoute(string $route): ?BreadcrumbInterface
    {
        return $this->getBreadcrumbs()->getBreadcrumbByRoute($route);
    }

    /**
     * Generates an instance of an implementation of BreadcrumbCollectionInterface,
     * based on the breadcrumb information given by the SF Request.
     */
    private function generateBreadcrumbCollectionFromRequest(): BreadcrumbCollectionInterface
    {
        /** @var BreadcrumbCollectionInterface $collection */
        $collection = new $this->collectionClass();

        $model = $this->modelClass;

        if (empty($rawCrumb = $this->requestBreadcrumbConfig)) {
            return $collection;
        }

        $collection->addBreadcrumb(new $model(
            $rawCrumb['label'],
            $rawCrumb['route']
        ));

        $trackRoutes = [];

        do {
            // If this route already is in the raw collection, there's likely a circular breadcrumb, which will cause memory exhaustion
            if (isset($trackRoutes[$rawCrumb['route']])) {
                throw new \LogicException(sprintf('Circular breadcrumbs detected at route "%s"', $rawCrumb['route']));
            }
            $collection->addBreadcrumbToStart(new $model(
                $rawCrumb['label'],
                $rawCrumb['route'],
            ));
            $trackRoutes[$rawCrumb['route']] = true;
            if (!isset($rawCrumb['parent_route'])) {
                break;
            }
            $pathInfo = $this->router->generate($rawCrumb['parent_route']);
            $match = $this->router->match($pathInfo);
            $rawCrumb = $match['_breadcrumbs'] ?? null;
        } while ($rawCrumb);

        return $collection;
    }
}
