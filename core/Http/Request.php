<?php

namespace Core\Http;

class Request 
{
    public function __construct()
    {
        $this->method = strtolower($_SERVER['REQUEST_METHOD'] ?? 'get');
        $this->query_params = $this->setQueryParams();
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getQueryParams()
    {
        return $this->quert_params;
    }

    public function getQueryParam($name)
    {
        if (!isset($this->query_params[$name])) {
            return null;
        }

        return $this->query_params[$name];
    }

    private function setQueryParams(): array
    {
        if (empty($_GET)) {
            return [];
        }
        $query = [];
        foreach ($_GET as $key => $value)
        {
            $query[$key] = $value;
        }

        return $query;
    }
}