<?php

namespace  ChannelEngine\PrestaShop\Classes\Business\Interfaces\RepositoryInterfaces;

use ChannelEngine\PrestaShop\Classes\Business\DomainModels\DomainProduct;

interface ProductRepositoryInterface {
    public function getProductsByLang(int $langId): array;

    public function getProductById($productId): ?DomainProduct;
}