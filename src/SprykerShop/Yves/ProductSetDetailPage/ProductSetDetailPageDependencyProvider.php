<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ProductSetDetailPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductSetDetailPage\Dependency\Client\ProductSetDetailPageToProductClientBridge;
use SprykerShop\Yves\ProductSetDetailPage\Dependency\Client\ProductSetDetailPageToProductSetClientBridge;

class ProductSetDetailPageDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_PRODUCT = 'CLIENT_PRODUCT';
    const CLIENT_PRODUCT_SET = 'CLIENT_PRODUCT_SET';
    const PLUGIN_PRODUCT_SET_DETAIL_PAGE_WIDGETS = 'PLUGIN_PRODUCT_SET_DETAIL_PAGE_WIDGETS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addProductClient($container);
        $container = $this->addProductSetClient($container);
        $container = $this->addProductSetDetailPageWidgetPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductClient(Container $container)
    {
        $container[self::CLIENT_PRODUCT] = function (Container $container) {
            return new ProductSetDetailPageToProductClientBridge($container->getLocator()->product()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductSetClient(Container $container)
    {
        $container[self::CLIENT_PRODUCT_SET] = function (Container $container) {
            return new ProductSetDetailPageToProductSetClientBridge($container->getLocator()->productSet()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductSetDetailPageWidgetPlugins(Container $container)
    {
        $container[self::PLUGIN_PRODUCT_SET_DETAIL_PAGE_WIDGETS] = function () {
            return $this->getProductSetDetailPageWidgetPlugins();
        };

        return $container;
    }

    /**
     * @return string[]
     */
    protected function getProductSetDetailPageWidgetPlugins(): array
    {
        return [];
    }
}
