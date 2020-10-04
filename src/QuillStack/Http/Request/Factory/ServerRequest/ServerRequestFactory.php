<?php

declare(strict_types=1);

namespace QuillStack\Http\Request\Factory\ServerRequest;

use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use QuillStack\Http\Request\Factory\Exceptions\ServerParamNotSetException;
use QuillStack\Http\Request\Factory\Exceptions\UnknownServerRequestClassException;
use QuillStack\Http\Request\ServerRequest;
use QuillStack\Http\Request\InputStream;
use QuillStack\Http\Request\Validators\ServerParamValidator;

class ServerRequestFactory implements ServerRequestFactoryInterface
{
    /**
     * @var string
     */
    private string $requestClass = ServerRequest::class;

    /**
     * @var ServerParamValidator
     */
    public ServerParamValidator $validator;

    /**
     * @param string $requestClass
     */
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
        $this->validator->setServerParams($serverParams)->validate();

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
