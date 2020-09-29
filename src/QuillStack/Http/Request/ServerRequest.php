<?php

declare(strict_types=1);

namespace QuillStack\Http\Request;

use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use QuillStack\Http\HeaderBag\HeaderBag;
use QuillStack\Http\Request\Exceptions\MethodNotImplementedException;
use QuillStack\Http\Request\Factory\Exceptions\RequestMethodNotKnownException;

class ServerRequest implements ServerRequestInterface
{
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';

    public const AVAILABLE_METHODS = [
        self::METHOD_GET,
        self::METHOD_POST,
    ];

    private string $method;
    private UriInterface $uri;
    private string $protocolVersion;
    private HeaderBag $headerBag;
    private ?StreamInterface $body;
    private array $serverParams;
    private array $cookieParams;
    private array $queryParams;
    private array $uploadedFiles;
    private array $parsedBody;
    private array $attributes = [];

    /**
     * ServerRequest constructor.
     *
     * @param string $method
     * @param UriInterface $uri
     * @param string $protocolVersion
     * @param HeaderBag $headerBag
     * @param StreamInterface|null $body
     * @param array $serverParams
     * @param array $cookieParams
     * @param array $queryParams
     * @param array $uploadedFiles
     * @param array $parsedBody
     */
    public function __construct(
        string $method,
        UriInterface $uri,
        string $protocolVersion,
        HeaderBag $headerBag,
        StreamInterface $body = null,
        array $serverParams = [],
        array $cookieParams = [],
        array $queryParams = [],
        array $uploadedFiles = [],
        array $parsedBody = []
    ) {
        $this->method = $method;
        $this->uri = $uri;
        $this->protocolVersion = $protocolVersion;
        $this->headerBag = $headerBag;
        $this->body = $body;
        $this->serverParams = $serverParams;
        $this->cookieParams = $cookieParams;
        $this->queryParams = $queryParams;
        $this->uploadedFiles = $uploadedFiles;
        $this->parsedBody = $parsedBody;
    }

    /**
     * {@inheritDoc}
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    /**
     * {@inheritDoc}
     */
    public function withProtocolVersion($version)
    {
        $new = clone $this;
        $new->protocolVersion = $version;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getHeaders()
    {
        return $this->headerBag->getHeaders();
    }

    /**
     * {@inheritDoc}
     */
    public function hasHeader($name)
    {
        return $this->headerBag->hasHeader($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getHeader($name)
    {
        return $this->headerBag->getHeader($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getHeaderLine($name)
    {
        return $this->headerBag->getHeaderLine($name);
    }

    /**
     * {@inheritDoc}
     */
    public function withHeader($name, $value)
    {
        return $this->headerBag->withHeader($name, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function withAddedHeader($name, $value)
    {
        return $this->headerBag->withAddedHeader($name, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function withoutHeader($name)
    {
        return $this->headerBag->withoutHeader($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * {@inheritDoc}
     */
    public function withBody(StreamInterface $body)
    {
        $new = clone $this;
        $new->body = $body;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getRequestTarget()
    {
        throw new MethodNotImplementedException('Method `getRequestTarget` not implemented');
    }

    /**
     * {@inheritDoc}
     */
    public function withRequestTarget($requestTarget)
    {
        throw new MethodNotImplementedException('Method `withRequestTarget` not implemented');
    }

    /**
     * {@inheritDoc}
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * {@inheritDoc}
     */
    public function withMethod($method)
    {
        $uppercaseMethod = strtoupper($method);

        if (!in_array($uppercaseMethod, self::AVAILABLE_METHODS, true)) {
            throw new RequestMethodNotKnownException("Method not known: {$method}");
        }

        $new = clone $this;
        $new->method = $uppercaseMethod;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * {@inheritDoc}
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $new = clone $this;
        $new->uri = $uri;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getServerParams()
    {
        return $this->serverParams;
    }

    /**
     * {@inheritDoc}
     */
    public function getCookieParams()
    {
        $this->cookieParams;
    }

    /**
     * {@inheritDoc}
     */
    public function withCookieParams(array $cookies)
    {
        $new = clone $this;
        $new->cookieParams = $cookies;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getQueryParams()
    {
        $this->queryParams;
    }

    /**
     * {@inheritDoc}
     */
    public function withQueryParams(array $query)
    {
        $new = clone $this;
        $new->queryParams = $query;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getUploadedFiles()
    {
        $this->uploadedFiles;
    }

    /**
     * {@inheritDoc}
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        $new = clone $this;
        $new->uploadedFiles = $uploadedFiles;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getParsedBody()
    {
        return $this->parsedBody;
    }

    /**
     * {@inheritDoc}
     */
    public function withParsedBody($data)
    {
        $new = clone $this;
        $new->parsedBody = $data;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * {@inheritDoc}
     */
    public function getAttribute($name, $default = null)
    {
        if (!isset($this->attributes[$name])) {
            return $default;
        }

        return $this->attributes[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function withAttribute($name, $value)
    {
        throw new MethodNotImplementedException('Method `withAttribute` not implemented');
    }

    /**
     * {@inheritDoc}
     */
    public function withoutAttribute($name)
    {
        throw new MethodNotImplementedException('Method `withoutAttribute` not implemented');
    }
}
