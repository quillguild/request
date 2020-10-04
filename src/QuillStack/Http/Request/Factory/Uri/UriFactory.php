<?php

declare(strict_types=1);

namespace QuillStack\Http\Request\Factory\Uri;

use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use QuillStack\Http\Request\Factory\Uri\Exceptions\UnknownSchemeException;
use QuillStack\Http\Request\Factory\Uri\Exceptions\UriException;
use QuillStack\Http\Request\Uri;

class UriFactory implements UriFactoryInterface
{
    public const DEFAULT_QUERY = '';
    public const DEFAULT_PATH = '/';

    private const COLON_DELIMITER = ':';
    private const SLASH_DELIMITER = '/';

    private const SCHEME_PORTS = [
        Uri::SCHEME_HTTPS => 443,
        Uri::SCHEME_HTTP => 80,
    ];

    /**
     * {@inheritDoc}
     */
    public function createUri(string $uri = ''): UriInterface
    {
        $slashArray = $this->getSlashArray($uri);
        $colonArray = $this->getColonArray($uri);

        $userInfoHostPort = $this->getUserInfoHostPort($slashArray);
        $scheme = $this->getScheme($colonArray);

        [$userInfo, $authority] = $this->getUserInfoAndAuthority($userInfoHostPort);
        [$host, $port] = $this->getHostAndPort($userInfoHostPort, $scheme);
        [$path, $query] = $this->getPathAndQuery($slashArray);

        return new Uri($scheme, $authority, $userInfo, $host, $port, $query, $path);
    }

    private function getUserInfoAndAuthority(string $userInfoHostPort): array
    {
        $atArray = $this->getAtArray($userInfoHostPort);

        if ($atArray) {
            return $atArray;
        }

        return ['', $userInfoHostPort];
    }

    private function getHostAndPort(string $userInfoHostPort, string $scheme): array
    {
        $hostArray = $this->getHostArray($userInfoHostPort);

        if ($hostArray) {
            return [
                $hostArray[0],
                (int) $hostArray[1],
            ];
        }

        return [
            $userInfoHostPort,
            $this->getPort($scheme),
        ];
    }

    private function getPathAndQuery(array $slashArray): array
    {
        $path = $slashArray[3] !== '' ? $slashArray[3] : self::DEFAULT_PATH;
        $query = self::DEFAULT_QUERY;

        if (strstr($path, Uri::QUERY_STRING_DELIMITER)) {
            $pathArray = explode(Uri::QUERY_STRING_DELIMITER, $path);
            $path = $pathArray[0];
            $query = $pathArray[1];
        }

        return [$path, $query];
    }

    /**
     * @param string $uri
     *
     * @return array
     */
    private function getSlashArray(string $uri): array
    {
        $slashArray = explode(self::SLASH_DELIMITER, $uri);

        if (!isset($slashArray[3])) {
            throw new UriException('Cannot determine the host from the URI');
        }

        return $slashArray;
    }

    private function getColonArray(string $uri): array
    {
        return explode(self::COLON_DELIMITER, $uri);
    }

    private function getScheme(array $colonArray): string
    {
        return $colonArray[0];
    }

    private function getUserInfoHostPort(array $slashArray): string
    {
        return $slashArray[2];
    }

    private function getAtArray(string $userInfoHostPort): array
    {
        if (!strstr($userInfoHostPort, Uri::USER_INFO_DELIMITER)) {
            return [];
        }

        return explode(Uri::USER_INFO_DELIMITER, $userInfoHostPort);
    }

    private function getHostArray(string $host): array
    {
        if (!strstr($host, Uri::PORT_DELIMITER)) {
            return [];
        }

        return explode(Uri::PORT_DELIMITER, $host);
    }

    private function getPort(string $scheme): int
    {
        if (!in_array($scheme, Uri::AVAILABLE_SCHEMES, true)) {
            throw new UnknownSchemeException("Scheme not known: {$scheme}");
        }

        return self::SCHEME_PORTS[$scheme];
    }
}
