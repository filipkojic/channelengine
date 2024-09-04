<?php

namespace ChannelEngine\PrestaShop\Classes;

use \Exception;

/**
 * Class Bootstrap
 *
 * This class initializes and registers all the necessary services and controllers.
 */
class Bootstrap
{
    /**
     * Initialize and register all services and controllers.
     *
     * @throws Exception
     */
    public static function initialize(): void
    {
        self::registerRepos();
        self::registerServices();
        self::registerControllers();
    }

    /**
     * Registers repository instances with the service registry.
     * @return void
     */
    protected static function registerRepos(): void
    {
    }

    /**
     * Registers service instances with the service registry.
     * @return void
     *
     * @throws Exception
     */
    protected static function registerServices(): void
    {
    }

    /**
     * Registers controller instances with the service registry.
     * @return void
     *
     * @throws Exception
     */
    protected static function registerControllers(): void
    {
    }
}