<?php

declare(strict_types=1);

namespace QuillStack\Http\Request\Factory\ServerRequest;

use Psr\Http\Message\ServerRequestInterface;

final class GivenRequestFromGlobalsFactory
{
    public RequestFromGlobalsFactory $requestFromGlobalsFactory;

    /**
     * @param string $requestClass
     *
     * @return ServerRequestInterface
     */
    public function createGivenServerRequest(string $requestClass): ServerRequestInterface
    {
        $this->requestFromGlobalsFactory
            ->serverRequestFactory
            ->setRequestClass($requestClass);

        return $this->requestFromGlobalsFactory->createServerRequest();
    }
}
