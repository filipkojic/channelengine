<?php

namespace ChannelEngine\PrestaShop\Classes\Utility;

use \Exception;

/**
 * Class ServiceRegistry
 *
 * Singleton class that implements the Service Registry design pattern.
 */
class ServiceRegistry extends Singleton
{
    /**
     * @var array An associative array holding the registered services.
     */
    private $services = [];

    /**
     * Register a service with the given key.
     *
     * @param string $key The key to identify the service.
     * @param mixed $service The service to register.
     */
    public function register(string $key, $service): void
    {
        $this->services[$key] = $service;
    }

    /**
     * Get a registered service by key.
     *
     * @param string $key The key identifying the service.
     * @return mixed The registered service.
     * @throws Exception If the service is not found.
     */
    public function get(string $key)
    {
        if (!isset($this->services[$key])) {
            throw new Exception("Service not found: " . $key);
        }

        return $this->services[$key];
    }
}
