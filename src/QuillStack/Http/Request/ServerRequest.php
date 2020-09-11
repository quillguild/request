<?php

declare(strict_types=1);

namespace QuillStack\Http\Request;

use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
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
    private array $headers;
    private array $headersKeys;
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
     * @param array $headers
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
        array $headers,
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
        $this->headers = $headers;
        $this->headersKeys = array_map('strtolower', array_keys($this->headers));
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
        $this->headers;
    }

    /**
     * {@inheritDoc}
     */
    public function hasHeader($name)
    {
        return in_array(strtolower($name), $this->headersKeys);
    }

    /**
     * {@inheritDoc}
     */
    private function getHeaderIndex($name)
    {
        return array_search(strtolower($name), $this->headersKeys);
    }

    /**
     * {@inheritDoc}
     */
    public function getHeader($name)
    {
        if (!$this->hasHeader($name)) {
            return [];
        }

        $index = $this->getHeaderIndex($name);

        return [
            $name => array_values($this->headers)[$index]
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getHeaderLine($name)
    {
        $header = $this->getHeader($name);

        if ($header === []) {
            return '';
        }

        return current($header);
    }

    /**
     * {@inheritDoc}
     */
    public function withHeader($name, $value)
    {
        if (!is_string($name)) {
            throw new InvalidArgumentException('Header name is not string');
        }

        if (!is_string($value) && !is_array($value)) {
            throw new InvalidArgumentException('Header value is not string or array');
        }

        $new = clone $this;
        $new->headers[$name] = $value;
        $keyName = strtolower($name);

        if (!in_array($keyName, $new->headersKeys)) {
            $new->headersKeys[] = $keyName;
        }

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function withAddedHeader($name, $value)
    {
        $new = clone $this;

        if ($this->hasHeader($name)) {
            return $new;
        }

        return $new->withHeader($name, $value);
    }

    /**
     * @param int $index
     *
     * @return string
     */
    private function getHeaderKeyByIndex(int $index): string
    {
        return array_keys($this->headers)[$index];
    }

    /**
     * {@inheritDoc}
     */
    public function withoutHeader($name)
    {
        $new = clone $this;

        if (!$this->hasHeader($name)) {
            return $new;
        }

        $index = $this->getHeaderIndex($name);
        $key = $this->getHeaderKeyByIndex($index);

        unset($new->headersKeys[$index]);
        unset($new->headers[$key]);

        return $new;
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

    public function getRequestTarget()
    {
        // TODO: Implement getRequestTarget() method.
    }

    public function withRequestTarget($requestTarget)
    {
        // TODO: Implement withRequestTarget() method.
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

    public function withCookieParams(array $cookies)
    {
        // TODO: Implement withCookieParams() method.
    }

    /**
     * {@inheritDoc}
     */
    public function getQueryParams()
    {
        $this->queryParams;
    }

    public function withQueryParams(array $query)
    {
        // TODO: Implement withQueryParams() method.
    }

    /**
     * {@inheritDoc}
     */
    public function getUploadedFiles()
    {
        $this->uploadedFiles;
    }

    public function withUploadedFiles(array $uploadedFiles)
    {
        // TODO: Implement withUploadedFiles() method.
    }

    /**
     * {@inheritDoc}
     */
    public function getParsedBody()
    {
        return $this->parsedBody;
    }

    public function withParsedBody($data)
    {
        // TODO: Implement withParsedBody() method.
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

    public function withAttribute($name, $value)
    {
        // TODO: Implement withAttribute() method.
    }

    public function withoutAttribute($name)
    {
        // TODO: Implement withoutAttribute() method.
    }
}