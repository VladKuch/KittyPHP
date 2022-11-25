<?php
namespace Core;

class Config 
{
    private $configs = [];

    public function __construct($path = '_settings.yaml')
    {
        $this->configs = \Symfony\Component\Yaml\Yaml::parseFile(ROOT_DIR . '/' . $path);
    }

    public function __set($key, $value)
    {
        $this->configs[$key] = $value;
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->configs)) {
            return $this->configs[$key];
        }

        return null;
    }

    public function __isset($key)
    {
        return isset($this->configs[$key]);
    }
}