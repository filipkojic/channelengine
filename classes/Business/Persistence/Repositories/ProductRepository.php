<?php

namespace ChannelEngine\PrestaShop\Classes\Business\Persistence\Repositories;

use ChannelEngine\PrestaShop\Classes\Business\DomainModels\DomainProduct;
use ChannelEngine\PrestaShop\Classes\Business\Interfaces\RepositoryInterfaces\ProductRepositoryInterface;
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
}
