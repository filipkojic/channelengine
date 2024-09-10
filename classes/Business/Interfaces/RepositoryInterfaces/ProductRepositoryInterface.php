<?php

namespace ChannelEngine\PrestaShop\Classes\Business\Interfaces\RepositoryInterfaces;

use ChannelEngine\PrestaShop\Classes\Business\DomainModels\DomainProduct;

/**
 * Interface for the Product Repository, defining methods for retrieving products from the database.
 */
interface ProductRepositoryInterface
{

    /**
     * Retrieves all products for a specific language.
     *
     * This method fetches a list of products based on the provided language ID.
     *
     * @param int $langId The ID of the language to filter products by.
     * @return array An array of products for the specified language.
     */
    public function getProductsByLang(int $langId): array;

    /**
     * Retrieves a product by its ID.
     *
     * This method returns a single product based on its ID. If the product does not exist,
     * it will return null.
     *
     * @param int|string $productId The ID of the product to retrieve.
     * @return DomainProduct|null The product object if found, or null if not.
     */
    public function getProductById($productId): ?DomainProduct;
}
