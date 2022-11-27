<?php

namespace Core;

class Kitty {
    private $di;
    public function __construct(\Core\Di $container) 
    {
        $this->di = $container;
    }

    public function run(): void
    {
        print_r("Фреймверк запущен! \n ");

        $this->di->router->handle($this->di->request, $this->di->uri);
    }
}