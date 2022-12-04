<?php

namespace Core\Http;

use \Psr\Http\Message\ServerRequestInterface;

class Request extends Message implements ServerRequestInterface
{
    public function __construct()
    {
        $this->method = strtolower($_SERVER['REQUEST_METHOD'] ?? 'get');
        $this->query_params = $this->setQueryParams();
    }
}