<?php

namespace ChannelEngine\PrestaShop\Classes\Business\Services;

use ChannelEngine\PrestaShop\Classes\Business\Interfaces\ProxyInterfaces\ChannelEngineProxyInterface;
use ChannelEngine\PrestaShop\Classes\Business\Interfaces\RepositoryInterfaces\ProductRepositoryInterface;
use ChannelEngine\PrestaShop\Classes\Business\Interfaces\ServiceInterfaces\SyncServiceInterface;
use ChannelEngine\PrestaShop\Classes\Proxy\ChannelEngineProxy;
use Product;

class SyncService implements SyncServiceInterface
{
    protected $channelEngineProxy;
    protected $productRepository;

    public function __construct(ChannelEngineProxyInterface $proxy, ProductRepositoryInterface $productRepository)
    {
        $this->channelEngineProxy = $proxy;
        $this->productRepository = $productRepository;
    }

    /**
     * Sinhronizacija proizvoda sa ChannelEngine.
     *
     * @param array $products
     * @return bool|array
     * @throws \Exception
     */
    public function startSync(int $langId): bool
    {
        // Dobavljanje proizvoda iz repozitorijuma
        $products = $this->productRepository->getProductsByLang($langId);

        // Pretvaranje proizvoda u asocijativni niz za API
        $formattedProducts = array_map(function($product) {
            return $product->toArray();
        }, $products);

        // Slanje proizvoda ka proxy-ju
        return $this->channelEngineProxy->syncProducts($formattedProducts);
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

