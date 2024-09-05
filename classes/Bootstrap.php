<?php

namespace ChannelEngine\PrestaShop\Classes;

use ChannelEngine\PrestaShop\Classes\Business\Interfaces\ProxyInterfaces\ChannelEngineProxyInterface;
use ChannelEngine\PrestaShop\Classes\Business\Interfaces\RepositoryInterfaces\ProductRepositoryInterface;
use ChannelEngine\PrestaShop\Classes\Business\Interfaces\ServiceInterfaces\LoginServiceInterface;
use ChannelEngine\PrestaShop\Classes\Business\Interfaces\ServiceInterfaces\SyncServiceInterface;
use ChannelEngine\PrestaShop\Classes\Business\Persistence\Repositories\ProductRepository;
use ChannelEngine\PrestaShop\Classes\Business\Services\LoginService;
use ChannelEngine\PrestaShop\Classes\Business\Services\SyncService;
use ChannelEngine\PrestaShop\Classes\Proxy\ChannelEngineProxy;
use ChannelEngine\PrestaShop\Classes\Utility\ServiceRegistry;
use Exception;

/**
 * Class Bootstrap
 *
 * This class initializes and registers all the necessary services and repositories.
 */
class Bootstrap
{
    /**
     * Initialize and register all proxies, repositories, and services.
     *
     * @throws Exception
     */
    public static function initialize(): void
    {
        self::registerProxy();
        self::registerRepos();
        self::registerServices();
    }

    /**
     * Registers proxy instances with the service registry.
     * @return void
     */
    protected static function registerProxy(): void
    {
        ServiceRegistry::getInstance()->register(ChannelEngineProxyInterface::class, new ChannelEngineProxy());
    }

    /**
     * Registers repository instances with the service registry.
     * @return void
     */
    protected static function registerRepos(): void
    {
        ServiceRegistry::getInstance()->register(ProductRepositoryInterface::class, new ProductRepository());
    }

    /**
     * Registers service instances with the service registry.
     * @return void
     *
     * @throws Exception
     */
    protected static function registerServices(): void
    {
        ServiceRegistry::getInstance()->register(LoginServiceInterface::class, new LoginService(
            ServiceRegistry::getInstance()->get(ChannelEngineProxyInterface::class)
        ));

        ServiceRegistry::getInstance()->register(SyncServiceInterface::class, new SyncService(
            ServiceRegistry::getInstance()->get(ChannelEngineProxyInterface::class),
            ServiceRegistry::getInstance()->get(ProductRepositoryInterface::class)
        ));
    }
}
