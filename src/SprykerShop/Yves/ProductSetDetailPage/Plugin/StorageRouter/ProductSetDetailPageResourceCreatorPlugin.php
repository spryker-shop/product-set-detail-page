<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetDetailPage\Plugin\StorageRouter;

use Generated\Shared\Transfer\ProductSetDataStorageTransfer;
use Spryker\Shared\ProductSetStorage\ProductSetStorageConstants;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StorageRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface;
use SprykerShop\Yves\ProductSetDetailPage\Controller\DetailController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductSetDetailPage\ProductSetDetailPageFactory getFactory()
 */
class ProductSetDetailPageResourceCreatorPlugin extends AbstractPlugin implements ResourceCreatorPluginInterface
{
    private const SERVICE_REQUEST = 'request';

    /**
     * @return string
     */
    public function getType(): string
    {
        return ProductSetStorageConstants::PRODUCT_SET_RESOURCE_NAME;
    }

    /**
     * @return string
     */
    public function getModuleName(): string
    {
        return 'ProductSetDetailPage';
    }

    /**
     * @return string
     */
    public function getControllerName(): string
    {
        return 'Detail';
    }

    /**
     * @return string
     */
    public function getActionName(): string
    {
        return 'index';
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function mergeResourceData(array $data): array
    {
        $productSetStorageTransfer = $this->getFactory()->getProductSetStorageClient()->mapProductSetStorageDataToTransfer($data);
        $productViewTransfers = $this->mapProductViewTransfer($productSetStorageTransfer);

        return [
            'productSetDataStorageTransfer' => $productSetStorageTransfer,
            'productViewTransfers' => $productViewTransfers,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetDataStorageTransfer $productSetDataStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function mapProductViewTransfer(ProductSetDataStorageTransfer $productSetDataStorageTransfer): array
    {
        $productViewTransfers = [];
        foreach ($productSetDataStorageTransfer->getProductAbstractIds() as $idProductAbstract) {
            $productViewTransfer = $this->getFactory()->getProductStorageClient()->findProductAbstractViewTransfer(
                $idProductAbstract,
                $this->getLocale(),
                $this->getSelectedAttributes($idProductAbstract)
            );

            if ($productViewTransfer === null) {
                continue;
            }

            $productViewTransfers[] = $productViewTransfer;
        }

        return $productViewTransfers;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    protected function getSelectedAttributes($idProductAbstract): array
    {
        $attributes = $this->getRequest()->query->get(DetailController::PARAM_ATTRIBUTE, []);

        return isset($attributes[$idProductAbstract]) ? array_filter($attributes[$idProductAbstract]) : [];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest(): Request
    {
        return $this->getApplication()->get(static::SERVICE_REQUEST);
    }
}
