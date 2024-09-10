<?php

namespace ChannelEngine\PrestaShop\Classes\Business\Services;

use ChannelEngine\PrestaShop\Classes\Business\Interfaces\ProxyInterfaces\ChannelEngineProxyInterface;
use ChannelEngine\PrestaShop\Classes\Business\Interfaces\RepositoryInterfaces\ProductRepositoryInterface;
use ChannelEngine\PrestaShop\Classes\Business\Interfaces\ServiceInterfaces\LoginServiceInterface;
use ChannelEngine\PrestaShop\Classes\Business\Interfaces\ServiceInterfaces\SyncServiceInterface;
use ChannelEngine\PrestaShop\Classes\Utility\ServiceRegistry;
use Exception;
use Product;

/**
 * Service class responsible for synchronizing products between PrestaShop and ChannelEngine.
 */
class SyncService implements SyncServiceInterface
{
    /**
     * @var ChannelEngineProxyInterface Proxy for interacting with the ChannelEngine API.
     */
    protected $channelEngineProxy;

    /**
     * @var ProductRepositoryInterface Repository for accessing PrestaShop product data.
     */
    protected $productRepository;

    /**
     * Constructor method for SyncService.
     *
     * @param ChannelEngineProxyInterface $proxy Proxy for API interaction.
     * @param ProductRepositoryInterface $productRepository Repository for product access.
     */
    public function __construct(ChannelEngineProxyInterface $proxy, ProductRepositoryInterface $productRepository)
    {
        $this->channelEngineProxy = $proxy;
        $this->productRepository = $productRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function startSync(int $langId): bool
    {
        $products = $this->productRepository->getProductsByLang($langId);

        $formattedProducts = array_map(function ($product) {
            return $product->toArray();
        }, $products);

        return $this->channelEngineProxy->syncProducts($formattedProducts);
    }

    /**
     * {@inheritDoc}
     */
    public function syncSingleProduct($productId): bool
    {
        if (!ServiceRegistry::getInstance()->get(LoginServiceInterface::class)->isUserLoggedIn()) {
            throw new Exception('Not connected to ChannelEngine. Please configure API credentials.');
        }

        $product = $this->productRepository->getProductById($productId);

        if (!$product) {
            throw new Exception('Product not found: ' . $productId);
        }

        $formattedProduct = $product->toArray();

        return $this->channelEngineProxy->syncProducts([$formattedProduct]);
    }

    /**
     * {@inheritDoc}
     */
    public function getFormattedProducts(int $langId): array
    {
        $allProducts = Product::getProducts($langId, 0, 0, 'id_product', 'ASC');

        $products = [];
        foreach ($allProducts as $productData) {
            $product = [
                'MerchantProductNo' => $productData['id_product'],
                'Name' => $productData['name'],
                'Description' => $productData['description'] ?? '',
                'Price' => (float)$productData['price'],
            ];
            $products[] = $product;
        }

        return $products;
    }
}
