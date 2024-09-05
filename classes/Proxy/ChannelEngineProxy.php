<?php

namespace ChannelEngine\PrestaShop\Classes\Proxy;

use ChannelEngine\PrestaShop\Classes\Utility\HttpClient;

class ChannelEngineProxy
{
    private $httpClient;
    private $apiKey;

    /**
     * ChannelEngineProxy constructor.
     *
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->httpClient = new HttpClient();
        $this->apiKey = $apiKey;
    }

    /**
     * Validate API key by sending a request to the ChannelEngine API.
     *
     * @return array
     * @throws \Exception
     */
    public function validateCredentials()
    {
        $url = 'https://logeecom-1-dev.channelengine.net/api/v2/settings?apikey=' . $this->apiKey;
        $headers = ['accept: application/json'];

        return $this->httpClient->get($url, $headers);
    }

    /**
     * Example of fetching product data from the API.
     *
     * @return array
     * @throws \Exception
     */
    public function fetchProducts()
    {
        $url = 'https://logeecom-1-dev.channelengine.net/api/v2/products?apikey=' . $this->apiKey;
        $headers = ['accept: application/json'];

        return $this->httpClient->get($url, $headers);
    }

    // Dodaj druge metode za interakciju sa ChannelEngine API-jem po potrebi
}

