<?php

namespace ChannelEngine\PrestaShop\Classes\Persistence\Repositories;

use ChannelEngine\PrestaShop\Classes\Business\DomainModels\DomainProduct;
use ChannelEngine\PrestaShop\Classes\Business\Interfaces\RepositoryInterfaces\ProductRepositoryInterface;
use Context;
use Product;

/**
 * Repository class responsible for retrieving product data from the database.
 * Implements ProductRepositoryInterface to define methods for fetching products.
 */
class ProductRepository implements ProductRepositoryInterface
{

    /**
     * {@inheritDoc}
     */
    public function getProductsByLang(int $langId): array
    {
        $rawProducts = Product::getProducts($langId, 0, 0, 'id_product', 'ASC');
        $products = [];

        foreach ($rawProducts as $rawProduct) {
            $products[] = new DomainProduct(
                $rawProduct['id_product'],
                $rawProduct['name'],
                $rawProduct['description'] ?? '',
                (float)$rawProduct['price']
            );
        }

        return $products;
    }

    /**
     * {@inheritDoc}
     */
    public function getProductById($productId): ?DomainProduct
    {
        $langId = (int)Context::getContext()->language->id;
        $product = new Product($productId, false, $langId);

        if (!$product->id) {
            return null;
        }

        return new DomainProduct(
            $product->id,
            $product->name,
            $product->description,
            (float)$product->price
        );
    }
}
