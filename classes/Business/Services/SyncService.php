<?php

namespace ChannelEngine\PrestaShop\Classes\Business\Services;

use ChannelEngine\PrestaShop\Classes\Business\Interfaces\ProxyInterfaces\ChannelEngineProxyInterface;
use ChannelEngine\PrestaShop\Classes\Business\Interfaces\ServiceInterfaces\SyncServiceInterface;
use ChannelEngine\PrestaShop\Classes\Proxy\ChannelEngineProxy;
use Product;

class SyncService implements SyncServiceInterface
{
    protected $channelEngineProxy;

    public function __construct(ChannelEngineProxyInterface $proxy)
    {
        $this->channelEngineProxy = $proxy;
    }

    /**
     * Sinhronizacija proizvoda sa ChannelEngine.
     *
     * @param array $products
     * @return bool|array
     * @throws \Exception
     */
    public function startSync(array $products)
    {
        $response = $this->channelEngineProxy->syncProducts($products);

        if ($response === true) {
            return true;
        }

        return $response;
    }

    /**
     * Preuzima i formatira proizvode iz PrestaShop baze.
     *
     * @param int $langId
     * @return array
     */
    public function getFormattedProducts(int $langId): array
    {
        // Dobavljanje liste proizvoda
        $allProducts = Product::getProducts($langId, 0, 0, 'id_product', 'ASC');

        // Formiranje podataka u formatu koji zahteva ChannelEngine API
        $products = [];
        foreach ($allProducts as $productData) {
            $product = [
                'MerchantProductNo' => $productData['id_product'], // Unique identifier in PrestaShop
                'Name' => $productData['name'],
                'Description' => $productData['description'] ?? '',
                'Price' => (float)$productData['price'],
            ];

            $products[] = $product;
        }

        return $products;
    }
}

