<?php
namespace Core\Http;

use Header\HeaderInterface;

class Header implements HeaderInterface
{
    protected array $globals;

    protected array $headers;

    /**
     * @param array   $headers
     */
    final public function __construct(array $headers = [])
    {
        $this->globals = $_SERVER;
        $this->headers = $headers;
    }

    /**
     * {@inheritdoc}
     */
    public function addHeader($name, $value): HeadersInterface
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeHeader(string $name): HeadersInterface
    {
        unset($this->headers[$name]);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader(string $name, $default = []): array
    {
        $name = $this->normalizeHeaderName($name);

        if (isset($this->headers[$name])) {
            return $this->headers[$name];
        }

        return $default;
    }

    /**
     * {@inheritdoc}
     */
    public function setHeader($name, $value): HeadersInterface
    {
        $normalizedName = $this->normalizeHeaderName($name);

        $this->headers[$normalizedName] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasHeader(string $name): bool
    {
        $name = $this->normalizeHeaderName($name);
        return isset($this->headers[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders(): array
    {
        $headers = [];

        foreach ($this->headers as $name => $header) {
            $headers[$name] = $header;
        }

        return $headers;
    }

    /**
     * @param string $name
     * @param bool   $preserveCase
     * @return string
     */
    protected function normalizeHeaderName(string $name, bool $preserveCase = false): string
    {
        $name = strtr($name, '_', '-');

        if (!$preserveCase) {
            $name = strtolower($name);
        }

        if (strpos(strtolower($name), 'http-') === 0) {
            $name = substr($name, 5);
        }

        return $name;
    }

    /**
     * Parse incoming headers and determine Authorization header from original headers
     *
     * @param array $headers
     * @return array
     */
    protected function parseAuthorizationHeader(array $headers): array
    {
        $hasAuthorizationHeader = false;
        foreach ($headers as $name => $value) {
            if (strtolower($name) === 'authorization') {
                $hasAuthorizationHeader = true;
                break;
            }
        }

        if (!$hasAuthorizationHeader) {
            if (isset($this->globals['REDIRECT_HTTP_AUTHORIZATION'])) {
                $headers['Authorization'] = $this->globals['REDIRECT_HTTP_AUTHORIZATION'];
            } elseif (isset($this->globals['PHP_AUTH_USER'])) {
                $pw = $this->globals['PHP_AUTH_PW'] ?? '';
                $headers['Authorization'] = 'Basic ' . base64_encode($this->globals['PHP_AUTH_USER'] . ':' . $pw);
            } elseif (isset($this->globals['PHP_AUTH_DIGEST'])) {
                $headers['Authorization'] = $this->globals['PHP_AUTH_DIGEST'];
            }
        }

        return $headers;
    }

    /**
     * @return static
     */
    public static function createFromGlobals()
    {
        $headers = null;

        if (function_exists('getallheaders')) {
            $headers = getallheaders();
        }

        if (!is_array($headers)) {
            $headers = [];
        }

        return new static($headers);
    }
}