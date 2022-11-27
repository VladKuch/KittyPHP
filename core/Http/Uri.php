<?php

namespace Core\Http;

use \Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
    private string $scheme;
    private string $user;
    private string $password;
    private string $host;
    private ?int $port;
    private string $path;
    private string $query;
    private string $fragment;

    public const REQUIRED_SCHEMES = [
        '' => null,
        'http' => 80,
        'https' => 443
    ];

    public function __construct($uri)
    {
        $parts = parse_url($uri);

        if ($parts === false) {
            throw new InvalidArgumentException('URI cannot be parsed');
        }

        $this->scheme   = $parts['scheme'] ?? '';
        $this->user     = $parts['user'] ?? '';
        $this->password = $parts['pass'] ?? '';
        $this->host     = $parts['host'] ?? '';
        $this->port     = $parts['port'] ?? null;
        $this->path     = $parts['path'] ?? '';
        $this->query    = $parts['query'] ?? '';
        $this->fragment = $parts['fragment'] ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthority(): string
    {
        $user_info = $this->getUserInfo();
        $host = $this->getHost();
        $port = $this->getPort();

        return ($user_info !== '' ? $user_info . '@' : '') . $host . ($port !== null ? ':' . $port : '');
    }

    /**
     * {@inheritdoc}
     */
    public function getUserInfo(): string
    {
        $info = $this->user;

        if ($this->password !== '') {
            $info .= ':' . $this->password;
        }

        return $info;
    }

    /**
     * {@inheritdoc}
     */
    public function getHost():string
    {
        return $this->host;
    }

     /**
     * {@inheritdoc}
     */
    public function getPort():?int
    {
        return $this->port;
    }

     /**
     * {@inheritdoc}
     */
    public function getPath():string
    {
        return $this->path;
    }

     /**
     * {@inheritdoc}
     */
    public function getQuery():string
    {
        return $this->query;
    }

     /**
     * {@inheritdoc}
     */
    public function getFragment():string
    {
        return $this->fragment;
    }

    /**
    * {@inheritdoc}
    */
    public function withScheme($scheme)
    {
        $clone = clone $this;
        $clone->scheme = $this->filterScheme($scheme);
        return $clone;
    }

    /**
     * Фильтр Uri схемы.
     *
     * @param  mixed $scheme
     *
     * @return string
     *
     * @throws InvalidArgumentException Если Uri схема не строка.
     * @throws InvalidArgumentException Если Uri схемы нет в REQUIREDED_SCHEMES
     */
    private function filterScheme($scheme): string
    {
        if (!is_string($scheme)) {
            throw new \InvalidArgumentException('Uri должно быть строкой.');
        }

        $scheme = str_replace('://', '', strtolower($scheme));
        if (!key_exists($scheme, static::REQUIRED_SCHEMES)) {
            throw new \InvalidArgumentException(
                'Uri схема должна быть одной из: "' . implode('", "', array_keys(static::REQUIRED_SCHEMES)) . '"'
            );
        }

        return $scheme;
    }
    /**
     * Return an instance with the specified user information.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified user information.
     *
     * Password is optional, but the user information MUST include the
     * user; an empty string for the user is equivalent to removing user
     * information.
     *
     * @param string $user The user name to use for authority.
     * @param null|string $password The password associated with $user.
     * @return static A new instance with the specified user information.
     */
    public function withUserInfo($user, $password = null)
    {
        $clone = clone $this;

        if (empty($user) || !is_string($user)) {
            $user = '';
        }

        if (empty($password) || !is_string($password)) {
            $password = '';
        }

        $clone->user = $user;
        $clone->password = $password;

        return $clone;
    }

    /**
     * Return an instance with the specified host.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified host.
     *
     * An empty host value is equivalent to removing the host.
     *
     * @param string $host The hostname to use with the new instance.
     * @return static A new instance with the specified host.
     * @throws \InvalidArgumentException for invalid hostnames.
     */
    public function withHost($host)
    {
        $clone = clone $this;
        $clone->host = $this->filterHost($host);
        return $clone;
    }

    private function filterHost($host)
    {
        if (!is_string($host)) {
            throw new \InvalidArgumentException('Uri хост должен быть строкой');
        }

        return $host;
    }

    /**
     * {@inheritdoc}
     */
    public function withPort($port)
    {
        $clone = clone $this;
        if (!empty($port) && !is_int($port)) {
            throw new \InvalidArgumentException('Uri порт должен быть целым числом.'); 
        }
        $clone->port = $port;
        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withPath($path): string
    {
        $clone = clone $this;
        $clone->path = $this->filterPath($path);
        return $clone;
    }

    private function filterPath($path): string
    {
        if (!is_string($path)) {
            throw new \InvalidArgumentException('Uri path должен быть строкой.'); 
        }
        $match = preg_replace_callback(
            '/(?:[^a-zA-Z0-9_\-\.~:@&=\+\$,\/;%]+|%(?![A-Fa-f0-9]{2}))/',
            function ($match) {
                return rawurlencode($match[0]);
            },
            $path
        );

        return is_string($match) ? $match : '';
    }

    /**
     * {@inheritdoc}
     */
    public function withQuery($query)
    {
        $clone = clone $this;
        if (!is_string($query)) {
            throw new \InvalidArgumentException('Uri query должен быть строкой.');
        }
        $clone->query = $query;
        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withFragment($fragment)
    {
        $clone = clone $this;
        if (!is_string($fragment)) {
            throw new \InvalidArgumentException('Uri query должен быть строкой.');
        }
        $clone->fragment = $fragment;
        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        $scheme = $this->getScheme();
        $authority = $this->getAuthority();
        $path = $this->getPath();
        $query = $this->getQuery();
        $fragment = $this->getFragment();

        if ($path !== '') {
            if ($path[0] !== '/') {
                if ($authority !== '') {
                    $path = '/' . $path;
                }
            } elseif (isset($path[1]) && $path[1] === '/') {
                if ($authority === '') {
                    $path = '/' . ltrim($path, '/');
                }
            }
        }

        return ($scheme !== '' ? $scheme . ':' : '')
            . ($authority !== '' ? '//' . $authority : '')
            . $path
            . ($query !== '' ? '?' . $query : '')
            . ($fragment !== '' ? '#' . $fragment : '');
    }
}