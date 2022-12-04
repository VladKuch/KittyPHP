<?php

namespace Core\Http;

use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\UriInterface;
use Psr\Http\Message\StreamInterface;
use Header\HeaderInterface;

class Request extends Message implements ServerRequestInterface
{
    private array $cookies;
    private array $attributes;
    private array $uploaded_files;
    private array $server_params;

    private string $method;
    private string $request_target;
    
    private $uri;
    private $query_params;
    private $parsed_body;
    
    public function __construct(
        $method,
        UriInterface $uri,
        HeaderInterface $headers,
        StreamInterface $body

    ) {
        $this->method = $method ?? 'get';
        $this->uri = $uri;
        $this->headers = $headers; 
        $this->body = $body; 
        $this->cookies = $_COOKIE;
        $this->server_params = $_SERVER;
        $this->uploaded_files = $_FILES;
        $this->attributes = [];
        
        if (isset($server_params['SERVER_PROTOCOL'])) {
            $this->protocol_version = str_replace('HTTP/', '', $server_params['SERVER_PROTOCOL']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCookiesParams(): array 
    {
        return $this->cookies;
    }

    /**
     * {@inheritdoc}
     */
    public function withCookiesParams(array $cookies)
    {
        $clone = clone $this;
        $clone->cookies = $cookies;
        return $clone;
    }

     /**
     * {@inheritdoc}
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * {@inheritdoc}
     * @return static
     */
    public function withMethod($method)
    {
        $clone = clone $this;
        $clone->method = $method;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function getServerParams(): array 
    {
        return $this->server_params;
    }

     /**
     * {@inheritdoc}
     */
    public function getQueryParams(): array
    {
        if (is_array($this->query_params)) {
            return $this->query_params;
        }

        if ($this->uri === null) {
            return [];
        }

        parse_str($this->uri->getQuery(), $this->query_params); 
        return $this->query_params;
    }

    /**
     * {@inheritdoc}
     * @return static
     */
    public function withQueryParams(array $query_params)
    {
        $clone = clone $this;
        $clone->query_params = $query_params;

        return $clone;
    }

     /**
     * {@inheritdoc}
     */
    public function getUploadedFiles(): array
    {
        return $this->uploaded_files;
    }

    /**
     * {@inheritdoc}
     * @return static
     */
    public function withUploadedFiles(array $uploaded_files)
    {
        $clone = clone $this;
        $clone->uploaded_files = $uploaded_files;

        return $clone;
    }

     /**
     * {@inheritdoc}
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     * @return mixed
     */
    public function getAttribute($name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * {@inheritdoc}
     * @return static
     */
    public function withAttribute($name, $value)
    {
        $clone = clone $this;
        $clone->attributes[$name] = $value;

        return $clone;
    }

    /**
     * {@inheritdoc}
     * @return static
     */
    public function withoutAttribute($name)
    {
        $clone = clone $this;

        unset($clone->attributes[$name]);

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function getParsedBody()
    {
        return $this->parsed_body;
    }

    /**
     * {@inheritdoc}
     * @return static
     */
    public function withParsedBody($body)
    {
        if (!is_null($body) && !is_object($body) && !is_array($body)) {
            throw new InvalidArgumentException('Parsed body value must be an array, an object, or null');
        }

        $clone = clone $this;
        $clone->parsed_body = $body;

        return $clone;
    }

    public function __clone()
    {
        $this->headers = clone $this->headers;
        $this->parsed_body = clone $this->parsed_body;
    }
}