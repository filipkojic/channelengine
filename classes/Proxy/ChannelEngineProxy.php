<?php

namespace ChannelEngine\PrestaShop\Classes\Proxy;

use ChannelEngine\PrestaShop\Classes\Business\Interfaces\ProxyInterfaces\ChannelEngineProxyInterface;
use ChannelEngine\PrestaShop\Classes\Utility\HttpClient;
use Configuration;
use PrestaShopLogger;

/**
 * Proxy class responsible for communication with the ChannelEngine API.
 * Implements ChannelEngineProxyInterface to validate credentials and synchronize products.
 */
class ChannelEngineProxy implements ChannelEngineProxyInterface
{
    /**
     * @var HttpClient Singleton instance for handling HTTP requests.
     */
    protected $httpClient;

    /**
     * Constructor initializes the HttpClient instance for making API calls.
     */
    public function __construct()
    {
        $this->httpClient = HttpClient::getInstance();
    }

    /**
     * {@inheritDoc}
     */
    public function validateCredentials(string $apiKey, string $accountName): bool
    {
        try {
            $url = 'https://' . $accountName . '.channelengine.net/api/v2/settings?apikey=' . $apiKey;
            $headers = ['Accept: application/json'];
            $response = $this->httpClient->get($url, $headers);

            return $this->validateResponse($response);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function syncProducts(array $products): bool
    {
        $url = 'https://' . Configuration::get('CHANNELENGINE_ACCOUNT_NAME') .
            '.channelengine.net/api/v2/products?apikey=' . Configuration::get('CHANNELENGINE_API_KEY');
        $headers = ['Content-Type: application/json'];
        $response = $this->httpClient->post($url, $products, $headers);

        return $this->validateResponse($response);
    }

    /**
     * Validates the API response and checks for success.
     *
     * This method verifies if the response from the ChannelEngine API indicates success.
     * If not, it throws an exception with the error message from the API.
     *
     * @param array $response The response received from the ChannelEngine API.
     * @return bool Returns true if the API call was successful.
     * @throws \Exception If the API response indicates an error or the success flag is missing.
     */
    private function validateResponse(array $response): bool
    {
        if (isset($response['Success']) && $response['Success'] === true) {
            return true;
        }

        throw new \Exception('API error: ' . ($response['Message'] ?? 'Unknown error'));
    }
}


