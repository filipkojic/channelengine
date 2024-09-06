<?php

namespace ChannelEngine\PrestaShop\Classes\Business\Persistence\Repositories;

use ChannelEngine\PrestaShop\Classes\Business\DomainModels\DomainProduct;
use ChannelEngine\PrestaShop\Classes\Business\Interfaces\RepositoryInterfaces\ProductRepositoryInterface;
use Context;
use Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function getProductsByLang(int $langId): array
    {
        $rawProducts = Product::getProducts($langId, 0, 0, 'id_product', 'ASC');
        $products = [];

        foreach ($rawProducts as $rawProduct) {
            $products[] = new DomainProduct(
                $rawProduct['id_product'],
                $rawProduct['name'],
                $rawProduct['description'] ?? '',
                (float) $rawProduct['price']
            );
        }

        return $products;
    }

    public function getProductById($productId): ?DomainProduct
    {
        $langId = (int)Context::getContext()->language->id; // Postavi jezik
        // Dohvati sve proizvode
        $allProducts = Product::getProducts($langId, 0, 0, 'id_product', 'ASC');

        // Filtriraj proizvode po zadatom ID-ju
        foreach ($allProducts as $productData) {
            if ((int)$productData['id_product'] === (int)$productId) {
                return new DomainProduct(
                    $productData['id_product'],
                    $productData['name'],
                    $productData['description'] ?? '',
                    (float)$productData['price']
                );
            }
        }

        // Ako proizvod nije pronađen, vraćamo null
        return null;
    }
}
