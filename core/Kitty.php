<?php

namespace Core;

class Kitty {
    private $di;
    public function __construct($container = []) 
    {
        $this->di = \Core\Di::getInstance();
        $this->di->initialize($container);
    }

    public function run(): void
    {
        print_r("Фреймверк запущен!");
    }
}