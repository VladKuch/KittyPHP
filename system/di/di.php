<?php

$container = \Core\Di::getInstance();
$container->config = function () {
    return new \Core\Config();
};

return $container;