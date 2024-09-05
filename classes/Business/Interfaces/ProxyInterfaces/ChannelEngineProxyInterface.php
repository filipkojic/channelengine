<?php

namespace ChannelEngine\PrestaShop\Classes\Business\Interfaces\ProxyInterfaces;

interface ChannelEngineProxyInterface
{
    /**
     * Validacija kredencijala sa prosleđenim API ključem.
     *
     * @param string $apiKey
     * @return mixed
     * @throws \Exception
     */
    public function validateCredentials($apiKey);

    /**
     * Sinhronizacija proizvoda sa ChannelEngine.
     *
     * @param array $products
     * @return mixed
     * @throws \Exception
     */
    public function syncProducts(array $products);
}
