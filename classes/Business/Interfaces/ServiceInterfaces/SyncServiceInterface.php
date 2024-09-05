<?php

namespace ChannelEngine\PrestaShop\Classes\Business\Interfaces\ServiceInterfaces;;

interface SyncServiceInterface
{
    /**
     * Sinhronizacija proizvoda sa ChannelEngine.
     *
     * @param array $products
     * @return bool|array
     * @throws \Exception
     */
    public function startSync(array $products);

    /**
     * Preuzima i formatira proizvode iz PrestaShop baze.
     *
     * @param int $langId
     * @return array
     */
    public function getFormattedProducts(int $langId): array;
}
