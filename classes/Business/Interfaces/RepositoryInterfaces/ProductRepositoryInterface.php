<?php

namespace  ChannelEngine\PrestaShop\Classes\Business\Interfaces\RepositoryInterfaces;

interface ProductRepositoryInterface {
    public function getProductsByLang(int $langId): array;
}