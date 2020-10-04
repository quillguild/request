<?php

declare(strict_types=1);

namespace QuillStack\Http\Request\Factory\ServerRequest;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriFactoryInterface;
use QuillStack\Http\HeaderBag\HeaderBag;
use QuillStack\Http\Request\Factory\Exceptions\RequestMethodNotKnownException;
use QuillStack\Http\Request\ServerRequest;
use QuillStack\Http\Request\Validators\ServerGlobalArrayValidator;
use QuillStack\Http\Uri\Uri;
use QuillStack\ParameterBag\ParameterBag;

class RequestFromGlobalsFactory
{
    /**
     * @var string
     */
    public const SERVER_REQUEST_METHOD = 'REQUEST_METHOD';

    /**
     * @var string
     */
    public const SERVER_HTTP_HOST = 'HTTP_HOST';

    /**
     * @var string
     */
    public const SERVER_REQUEST_URI = 'REQUEST_URI';

    /**
     * @var string
     */
    public const SERVER_SERVER_PROTOCOL = 'SERVER_PROTOCOL';

    /**
     * @var string
     */
    private const SERVER_HTTPS = 'HTTPS';

    /**
     * @var string
     */
    private const HEADER_PREFIX = 'HTTP_';

    /**
     * @var ServerRequestFactory
     */
    public ServerRequestFactory $serverRequestFactory;

    /**
     * @var UriFactoryInterface
     */
    public UriFactoryInterface $uriFactory;

    /**
     * @var ServerGlobalArrayValidator
     */
    public ServerGlobalArrayValidator $validator;

    /**
     * @var array
     */
    private array $server;

    /**
     * @var array
     */
    private array $cookie;

    /**
     * @var array
     */
    private array $query;

    /**
     * @var array
     */
    private array $files;

    /**
     * @var array
     */
    private array $post;

    /**
     * @param array $server
     * @param array $cookie
     * @param array $query
     * @param array $files
     * @param array $post
     */
    public function __construct(
        array $server = [],
        array $cookie = [],
        array $query = [],
        array $files = [],
        array $post = []
    ) {
        $this->server = !empty($server) ? $server : $_SERVER;
        $this->cookie = !empty($cookie) ? $cookie : $_COOKIE;
        $this->query = !empty($query) ? $query : $_GET;
        $this->files = !empty($files) ? $files : $_FILES;
        $this->post = !empty($post) ? $post : $_POST;
    }

    /**
     * @return ServerRequestInterface
     */
    public function createServerRequest(): ServerRequestInterface
    {
        // Validate.
        $this->validator->setServer($this->server)->validate();

        // Set method, server params, and URI.
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
            'cookieParams' => new ParameterBag($this->cookie),
            'queryParams' => new ParameterBag($this->query),
            'uploadedFiles' => new ParameterBag($this->files),
            'parsedBody' => new ParameterBag($this->post),
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
