<?php

$version_lower = version_compare($current_version = phpversion(), $required = \Core\Constants::MIN_PHP_VERSION, '<');
if ($version_lower) {
    exit(sprintf('<h1 style="font-family: sans-serif;font-weight: 100;">Ваша текущая версия PHP %s, но для KittyPHP минимальная версия PHP должна быть %s.</h1>', $current_version, $required));
}
$container = require_once 'di/di.php';

$kitty = new \Core\Kitty($container);

try {
    $kitty->run();
} catch (\Throwable $error) {
    echo $error->getMessage() . '<br>' . $error->getTraceAsString();
}
