<?php

namespace ChannelEngine\PrestaShop\Classes\Proxy;

use ChannelEngine\PrestaShop\Classes\Business\Interfaces\ProxyInterfaces\ChannelEngineProxyInterface;
use ChannelEngine\PrestaShop\Classes\Utility\HttpClient;
use Configuration;

class ChannelEngineProxy implements ChannelEngineProxyInterface
{
    protected $httpClient;

    public function __construct()
    {
        $this->httpClient = HttpClient::getInstance();
    }

    /**
     * Validacija kredencijala sa prosleđenim API ključem.
     *
     * @param string $apiKey
     * @return mixed
     * @throws \Exception
     */
    public function validateCredentials($apiKey)
    {
        $url = 'https://logeecom-1-dev.channelengine.net/api/v2/settings?apikey=' . $apiKey;

        $headers = ['Accept: application/json'];

        $response = $this->httpClient->get($url, $headers);

        return $this->validateResponse($response);
    }

    /**
     * Sinhronizacija proizvoda sa ChannelEngine.
     *
     * @param array $products
     * @return mixed
     * @throws \Exception
     */
    public function syncProducts(array $products)
    {
        $url = 'https://' . Configuration::get('CHANNELENGINE_ACCOUNT_NAME') . '.channelengine.net/api/v2/products?apikey=' . Configuration::get('CHANNELENGINE_API_KEY');

        $headers = ['Content-Type: application/json'];

        $response = $this->httpClient->post($url, $products, $headers);

        return $this->validateResponse($response);
    }

    /**
     * Proverava da li je odgovor uspešan.
     *
     * @param array $response
     * @return bool
     * @throws \Exception
     */
    private function validateResponse($response)
    {
        if (isset($response['Success']) && $response['Success'] === true) {
            return true;
        }
        throw new \Exception('API error: ' . ($response['Message'] ?? 'Unknown error'));
    }
}


