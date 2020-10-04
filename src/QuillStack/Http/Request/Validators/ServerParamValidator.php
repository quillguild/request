<?php

declare(strict_types=1);

namespace QuillStack\Http\Request\Validators;

use QuillStack\Http\Request\Factory\Exceptions\ServerParamNotSetException;
use QuillStack\ValidatorInterface;

final class ServerParamValidator implements ValidatorInterface
{
    /**
     * @var array
     */
    private const REQUIRED_SERVER_PARAMS = [
        'protocolVersion',
        'headers',
        'serverParams',
        'cookieParams',
        'queryParams',
        'uploadedFiles',
        'parsedBody',
    ];

    /**
     * @var array
     */
    private array $serverParams;

    /**
     * @param array $serverParams
     *
     * @return $this
     */
    public function setServerParams(array $serverParams): self
    {
        $this->serverParams = $serverParams;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function validate(): bool
    {
        foreach (self::REQUIRED_SERVER_PARAMS as $requiredServerParam) {
            if (!isset($this->serverParams[$requiredServerParam])) {
                throw new ServerParamNotSetException("Server param not set: {$requiredServerParam}");
            }
        }

        return true;
    }
}
