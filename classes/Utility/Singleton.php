<?php

namespace ChannelEngine\PrestaShop\Classes\Utility;

/**
 * Class Singleton
 *
 * Provides a base class for implementing the Singleton design pattern.
 * Ensures that only one instance of the class exists throughout the application.
 */
abstract class Singleton
{
    /**
     * @var array Stores instances of the Singleton subclasses.
     */
    protected static $instances = [];

    /**
     * Protected constructor to prevent creating a new instance of the class via the 'new' operator.
     */
    protected function __construct()
    {
    }

    /**
     * Get the single instance of the class.
     *
     * @return static The single instance of the called class.
     */
    public static function getInstance()
    {
        $class = static::class;
        if (!isset(static::$instances[$class])) {
            static::$instances[$class] = new static();
        }

        return static::$instances[$class];
    }
}
