<?php

namespace ChannelEngine\PrestaShop\Classes\Business\Services;

use ChannelEngine\PrestaShop\Classes\Business\Interfaces\ServiceInterfaces\LoginServiceInterface;
use ChannelEngine\PrestaShop\Classes\Proxy\ChannelEngineProxy;
use Configuration;

class LoginService implements LoginServiceInterface
{
    protected $channelEngineProxy;

    public function __construct()
    {
        $this->channelEngineProxy = new ChannelEngineProxy();
    }

    /**
     * Validacija kredencijala i postavljanje API parametara.
     *
     * @param string $apiKey
     * @param string $accountName
     * @return bool
     * @throws \Exception
     */
    public function handleLogin(string $apiKey, string $accountName): bool
    {
        $response = $this->channelEngineProxy->validateCredentials($apiKey);

        if ($response === true) {
            Configuration::updateValue('CHANNELENGINE_ACCOUNT_NAME', $accountName);
            Configuration::updateValue('CHANNELENGINE_API_KEY', $apiKey);
            return true;
        }

        return false;
    }
}
