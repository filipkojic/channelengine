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

        // Kreiraj objekat proizvoda na osnovu ID-ja
        $product = new Product($productId, false, $langId);

        // Proveri da li je proizvod validan (postoji u bazi)
        if (!$product->id) {
            return null; // Ako proizvod ne postoji, vraćamo null
        }

        // Vraćamo instancu domenskog modela proizvoda
        return new DomainProduct(
            $product->id,
            $product->name, // Višejezično polje name
            $product->description, // Višejezično polje description
            (float)$product->price // Cena proizvoda
        );
    }

}
