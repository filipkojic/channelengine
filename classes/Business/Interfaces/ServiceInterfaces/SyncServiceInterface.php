<?php

namespace ChannelEngine\PrestaShop\Classes\Business\Interfaces\ServiceInterfaces;
;

/**
 * Interface for the Sync Service, defining methods for synchronizing products with ChannelEngine.
 */
interface SyncServiceInterface
{
    /**
     * Synchronizes products with ChannelEngine.
     *
     * This method starts the synchronization process for products based on the provided language ID.
     *
     * @param int $langId The ID of the language for which products should be synchronized.
     * @return bool True if synchronization is successful, or an array of errors if it fails.
     * @throws \Exception If synchronization fails or the API returns an error.
     */
    public function startSync(int $langId): bool;

    /**
     * Synchronizes a single product with ChannelEngine.
     *
     * This method synchronizes a specific product based on its ID.
     *
     * @param int $productId The ID of the product to be synchronized.
     * @return bool True if the product was successfully synchronized, false otherwise.
     * @throws \Exception If synchronization fails or the API returns an error.
     */
    public function syncSingleProduct(int $productId): bool;


    /**
     * Retrieves and formats products from the PrestaShop database.
     *
     * This method fetches all products for the specified language and formats them
     * according to ChannelEngine's requirements for synchronization.
     *
     * @param int $langId The ID of the language for which products should be fetched and formatted.
     * @return array An array of formatted products ready to be synchronized with ChannelEngine.
     */
    public function getFormattedProducts(int $langId): array;
}
