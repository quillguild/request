<?php

declare(strict_types=1);

namespace QuillStack\Http\Request\Factory\ServerRequest;

use Psr\Http\Message\ServerRequestInterface;
use QuillStack\Http\HeaderBag\HeaderBag;
use QuillStack\Http\Request\Factory\Exceptions\RequestMethodNotKnownException;
use QuillStack\Http\Request\Factory\Exceptions\RequiredParamFromGlobalsNotFoundException;
use QuillStack\Http\Request\Factory\Uri\UriFactory;
use QuillStack\Http\Request\ServerRequest;
use QuillStack\Http\Request\Uri;
use QuillStack\ParameterBag\ParameterBag;

class RequestFromGlobalsFactory
{
    private const SERVER_REQUEST_METHOD = 'REQUEST_METHOD';
    private const SERVER_HTTP_HOST = 'HTTP_HOST';
    private const SERVER_REQUEST_URI = 'REQUEST_URI';
    private const SERVER_SERVER_PROTOCOL = 'SERVER_PROTOCOL';
    private const SERVER_HTTPS = 'HTTPS';
    private const HEADER_PREFIX = 'HTTP_';

    private const REQUIRED_SERVER_PARAMS = [
        self::SERVER_REQUEST_METHOD,
        self::SERVER_HTTP_HOST,
        self::SERVER_REQUEST_URI,
        self::SERVER_SERVER_PROTOCOL,
    ];

    /**
     * @var ServerRequestFactory
     */
    public ServerRequestFactory $serverRequestFactory;

    /**
     * @var UriFactory
     */
    public UriFactory $uriFactory;

    /**
     * @var array
     */
    private array $server = [];

    /**
     * @param array $server
     */
    public function __construct(array $server = [])
    {
        $this->server = !empty($server) ? $server : $_SERVER;
    }

    /**
     * @return ServerRequestInterface
     */
    public function createServerRequest(): ServerRequestInterface
    {
        foreach (self::REQUIRED_SERVER_PARAMS as $requiredServerParam) {
            if (!isset($this->server[$requiredServerParam])) {
                throw new RequiredParamFromGlobalsNotFoundException("Not found: \$_SERVER['{$requiredServerParam}']");
            }
        }

        $method = $this->getMethod();
        $serverParams = $this->getServerParams();
        $uri = $this->uriFactory->createUri(
            $this->getUriString()
        );

        return $this->serverRequestFactory->createServerRequest($method, $uri, $serverParams);
    }

    /**
     * @return string
     */
    private function getMethod(): string
    {
        $method = strtoupper($this->server[self::SERVER_REQUEST_METHOD]);

        if (!in_array($method, ServerRequest::AVAILABLE_METHODS, true)) {
            throw new RequestMethodNotKnownException("Method not known: {$method}");
        }

        return $method;
    }

    /**
     * @return string
     */
    private function getUriString(): string
    {
        $scheme = isset($this->server[self::SERVER_HTTPS]) && $this->server[self::SERVER_HTTPS] === 'on'
            ? Uri::SCHEME_HTTPS
            : Uri::SCHEME_HTTP;

        $host = $this->server[self::SERVER_HTTP_HOST];
        $requestUri = $this->server[self::SERVER_REQUEST_URI];

        return "{$scheme}://{$host}{$requestUri}";
    }

    /**
     * @return array
     */
    private function getServerParams(): array
    {
        return [
            'protocolVersion' => $this->getServerVersion(),
            'headers' => $this->getHeaders(),
            'serverParams' => new ParameterBag($this->server),
            'cookieParams' => new ParameterBag($_COOKIE),
            'queryParams' => new ParameterBag($_GET),
            'uploadedFiles' => new ParameterBag($_FILES),
            'parsedBody' => new ParameterBag($_POST),
        ];
    }

    /**
     * @return HeaderBag
     */
    private function getHeaders(): HeaderBag
    {
        $headers = [];

        foreach ($this->server as $key => $value) {
            if (substr($key, 0, 5) !== self::HEADER_PREFIX) {
                continue;
            }

            $name = str_replace(self::HEADER_PREFIX, '', $key);
            $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $name))));

            $headers[$name] = $value;
        }

        return new HeaderBag($headers);
    }

    /**
     * @return string
     */
    private function getServerVersion(): string
    {
        return str_replace('HTTP/', '', $this->server['SERVER_PROTOCOL']);
    }
}
