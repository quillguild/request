<?php

declare(strict_types=1);

namespace QuillStack\Http\Request\Factory\ServerRequest;

use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use QuillStack\Http\Request\Factory\Exceptions\ServerParamNotSetException;
use QuillStack\Http\Request\Factory\Exceptions\UnknownServerRequestClassException;
use QuillStack\Http\Request\ServerRequest;
use QuillStack\Http\Request\InputStream;

class ServerRequestFactory implements ServerRequestFactoryInterface
{
    private const REQUIRED_SERVER_PARAMS = [
        'protocolVersion',
        'headers',
        'serverParams',
        'cookieParams',
        'queryParams',
        'uploadedFiles',
        'parsedBody',
    ];

    private string $requestClass = ServerRequest::class;

    public function setRequestClass(string $requestClass)
    {
        if (!class_exists($requestClass)) {
            throw new UnknownServerRequestClassException("Unknown class name: {$requestClass}");
        }

        $this->requestClass = $requestClass;
    }

    /**
     * {@inheritDoc}
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        foreach (self::REQUIRED_SERVER_PARAMS as $requiredServerParam) {
            if (!isset($serverParams[$requiredServerParam])) {
                throw new ServerParamNotSetException("Server param not set: {$requiredServerParam}");
            }
        }

        return new $this->requestClass(
            $method,
            $uri,
            $serverParams['protocolVersion'],
            $serverParams['headers'],
            new InputStream(),
            $serverParams['serverParams'],
            $serverParams['cookieParams'],
            $serverParams['queryParams'],
            $serverParams['uploadedFiles'],
            $serverParams['parsedBody'],
        );
    }
}
