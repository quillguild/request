<?php

declare(strict_types=1);

namespace QuillStack\Http\Request;

use Psr\Http\Message\UriInterface;

final class Uri implements UriInterface
{
    public const PORT_DELIMITER = ':';
    public const USER_INFO_DELIMITER = '@';
    public const QUERY_STRING_DELIMITER = '?';

    public const DEFAULT_PORT_HTTP = 80;
    public const DEFAULT_PORT_HTTPS = 443;

    public const DEFAULT_PORTS = [
        self::DEFAULT_PORT_HTTP,
        self::DEFAULT_PORT_HTTPS,
    ];

    public const SCHEME_HTTP = 'http';
    public const SCHEME_HTTPS = 'https';

    public const AVAILABLE_SCHEMES = [
        self::SCHEME_HTTP,
        self::SCHEME_HTTPS,
    ];

    private string $scheme;
    private string $authority;
    private string $userInfo = '';
    private string $host = '';
    private int $port;
    private string $query;
    private string $path;
    private string $fragment = '';

    /**
     * Uri constructor.
     *
     * @param string $scheme
     * @param string $authority
     * @param string $userInfo
     * @param string $host
     * @param int $port
     * @param string $query
     * @param string $path
     */
    public function __construct(
        string $scheme,
        string $authority,
        string $userInfo,
        string $host,
        int $port,
        string $query,
        string $path
    ) {
        $this->scheme = $scheme;
        $this->authority = $authority;
        $this->userInfo = $userInfo;
        $this->host = $host;
        $this->port = $port;
        $this->query = $query;
        $this->path = $path;
    }

    /**
     * {@inheritDoc}
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * {@inheritDoc}
     */
    public function getAuthority()
    {
        return $this->authority;
    }

    /**
     * {@inheritDoc}
     */
    public function getUserInfo()
    {
        return $this->authority;
    }

    /**
     * {@inheritDoc}
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * {@inheritDoc}
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritDoc}
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * {@inheritDoc}
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * {@inheritDoc}
     */
    public function withScheme($scheme)
    {
        $new = clone $this;
        $new->scheme = $scheme;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function withUserInfo($user, $password = null)
    {
        $new = clone $this;
        $new->userInfo = $password ? "{$user}:{$password}" : $user;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function withHost($host)
    {
        $new = clone $this;
        $new->host = $host;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function withPort($port)
    {
        $new = clone $this;
        $new->port = $port;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function withPath($path)
    {
        $new = clone $this;
        $new->path = $path;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function withQuery($query)
    {
        $new = clone $this;
        $new->query = $query;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function withFragment($fragment)
    {
        $new = clone $this;
        $new->fragment = $fragment;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        $uri = "{$this->scheme}://";

        if ($this->userInfo) {
            $uri .= "{$this->userInfo}@";
        }

        $uri .= $this->host;

        $wrongHttp = $this->port === self::DEFAULT_PORT_HTTP && $this->scheme !== self::SCHEME_HTTP;
        $wrongHttps = $this->port === self::DEFAULT_PORT_HTTPS && $this->scheme !== self::SCHEME_HTTPS;
        $noDefault = !in_array($this->port, self::DEFAULT_PORTS, true);

        if ($wrongHttp || $wrongHttps | $noDefault) {
            $uri .= ":{$this->port}";
        }

        if ($this->path !== '/') {
            $uri .= "/{$this->path}";
        }

        if ($this->query) {
            $uri .= "?{$this->query}";
        }

        if ($this->fragment) {
            $uri .= "#{$this->query}";
        }

        return $uri;
    }
}
