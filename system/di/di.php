<?php

$container = \Core\Di::getInstance();

$container->config = new \Core\Config();

$container->router = require_once ROOT_DIR . '/system/router/router.php';

return $container;