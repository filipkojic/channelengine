<?php

namespace ChannelEngine\PrestaShop\Classes\Business\Interfaces\ProxyInterfaces;

/**
 * Interface for ChannelEngineProxy, defining methods for API interactions
 * such as validating credentials and syncing products with ChannelEngine.
 */
interface ChannelEngineProxyInterface
{
    /**
     * Validates the provided API key with the ChannelEngine service.
     *
     * This method checks whether the API key is correct and can be used
     * for future API requests.
     *
     * @param string $apiKey The API key to be validated.
     * @return bool True if the API key is valid, false otherwise.
     * @throws \Exception Throws an exception if validation fails or the API is unreachable.
     */
    public function validateCredentials(string $apiKey): bool;

    /**
     * Synchronizes products with ChannelEngine by sending product data.
     *
     * This method sends the provided array of products to the ChannelEngine API
     * for synchronization.
     *
     * @param array $products The array of products to be synchronized.
     * @return bool True if the synchronization was successful, false otherwise.
     * @throws \Exception Throws an exception if the sync process fails or the API returns an error.
     */
    public function syncProducts(array $products): bool;
}
