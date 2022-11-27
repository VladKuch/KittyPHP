<?php
namespace Core\Http\Message;

use Psr\Http\Mesage\MesageInterface;
use Psr\Http\Mesage\StreamInterface;

abstract class Message extends MessageInterface 
{
    protected string $protocol_version = '1.1';
    protected array $headers;
    protected $body;
    protected array $required_versions  = ['1.0', '1.1', '2.0'];


    /**
     * {@inheritdoc}
     */
    public function getProtocolVersion(): string
    {
        return $this->protocol_version;
    }

    /**
     * {@inheritdoc}
     */
    public function withProtocolVersion($version)
    {
        if (!in_array($version, $this->$required_versions)) {
            throw new \InvalidArgumentException(
                'Неправильная версия HTTP. Должна быть одна из: '
                . implode(', ', array_keys(self::$validProtocolVersions))
            );
        }

        $clone = clone $this;
        $clone->protocol_мersion = $version;

        return $clone;
    }

     /**
     * {@inheritdoc}
     */
    public function getHeaders(): array
    {
        return $this->headers->getHeaders(true);
    }

    /**
     * {@inheritdoc}
     */
    public function hasHeader($name): bool
    {
        return $this->headers->hasHeader($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader($name): array
    {
        return $this->headers->getHeader($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaderLine($name): string
    {
        $values = $this->headers->getHeader($name);
        return implode(',', $values);
    }

    /**
     * {@inheritdoc}
     */
    public function withHeader($name, $value)
    {
        $clone = clone $this;
        $clone->headers->setHeader($name, $value);

        if ($this instanceof Response) {
            header(sprintf('%s: %s', $name, $clone->getHeaderLine($name)));
        }

        return $clone;
    }

    /**
     * @return static
     * {@inheritdoc}
     */
    public function withAddedHeader($name, $value)
    {
        $clone = clone $this;
        $clone->headers->addHeader($name, $value);

        if ($this instanceof Response) {
            header(sprintf('%s: %s', $name, $clone->getHeaderLine($name)));
        }

        return $clone;
    }

    /**
     * @return static
     * {@inheritdoc}
     */
    public function withoutHeader($name)
    {
        $clone = clone $this;
        $clone->headers->removeHeader($name);

        if ($this instanceof Response) {
            header_remove($name);
        }

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    /**
     * @return static
     * {@inheritdoc}
     */
    public function withBody(StreamInterface $body)
    {
        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }
}