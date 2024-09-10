<?php

namespace ChannelEngine\PrestaShop\Classes\Business\Services;

use ChannelEngine\PrestaShop\Classes\Business\Interfaces\ProxyInterfaces\ChannelEngineProxyInterface;
use ChannelEngine\PrestaShop\Classes\Business\Interfaces\ServiceInterfaces\LoginServiceInterface;
use Configuration;

/**
 * Service responsible for handling login and validation of ChannelEngine API credentials.
 */
class LoginService implements LoginServiceInterface
{
    /**
     * @var ChannelEngineProxyInterface Instance of the ChannelEngine proxy used for API validation.
     */
    protected $channelEngineProxy;

    /**
     * Constructor method to initialize the ChannelEngine proxy.
     *
     * @param ChannelEngineProxyInterface $proxy The proxy used for interacting with the ChannelEngine API.
     */
    public function __construct(ChannelEngineProxyInterface $proxy)
    {
        $this->channelEngineProxy = $proxy;
    }

    /**
     * {@inheritDoc}
     */
    public function handleLogin(string $apiKey, string $accountName): bool
    {
        $response = $this->channelEngineProxy->validateCredentials($apiKey, $accountName);

        if ($response === true) {
            Configuration::updateValue('CHANNELENGINE_ACCOUNT_NAME', $accountName);
            Configuration::updateValue('CHANNELENGINE_API_KEY', $apiKey);
            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function isUserLoggedIn(): bool
    {
        $apiKey = Configuration::get('CHANNELENGINE_API_KEY');
        $accountName = Configuration::get('CHANNELENGINE_ACCOUNT_NAME');

        return $apiKey && $accountName;
    }
}
