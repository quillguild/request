<?php

declare(strict_types=1);

namespace QuillStack\Http\Request\Validators;

use QuillStack\Http\Request\Factory\Exceptions\RequiredParamFromGlobalsNotFoundException;
use QuillStack\Http\Request\Factory\ServerRequest\RequestFromGlobalsFactory;
use QuillStack\ValidatorInterface;

final class ServerGlobalArrayValidator implements ValidatorInterface
{
    /**
     * @var array
     */
    private const REQUIRED_SERVER_PARAMS = [
        RequestFromGlobalsFactory::SERVER_REQUEST_METHOD,
        RequestFromGlobalsFactory::SERVER_HTTP_HOST,
        RequestFromGlobalsFactory::SERVER_REQUEST_URI,
        RequestFromGlobalsFactory::SERVER_SERVER_PROTOCOL,
    ];

    /**
     * @var array
     */
    private array $server;

    /**
     * @param array $server
     *
     * @return ServerGlobalArrayValidator
     */
    public function setServer(array $server): self
    {
        $this->server = $server;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function validate(): bool
    {
        foreach (self::REQUIRED_SERVER_PARAMS as $requiredServerParam) {
            if (!isset($this->server[$requiredServerParam])) {
                throw new RequiredParamFromGlobalsNotFoundException("Not found: \$_SERVER['{$requiredServerParam}']");
            }
        }

        return true;
    }
}
