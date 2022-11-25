<?php

namespace Core;

/**
 * Dependency Injection Class.
 *
 * Class Container
 * @package Core\DI
 */
class Di
{
    private static $instance = null;
    private $container = [];

    public static function  getInstance(): Di
    {
        if (self::$instance == null) {
            self::$instance = new Di();
        }

        return self::$instance;
    }

    public function initialize(array $container): void
    {
        $this->container = $container;
    }

    public function __set($key, $value)
    {
        if ($value instanceof \Closure) {
            $value = $value();
        }

        $this->container[$key] = $value;
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->container)) {
            return $this->container[$key];
        }

        return null;
    }

    public function __isset($key)
    {
        return isset($this->container[$key]);
    }

    private function __construct() {}
}