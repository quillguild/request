<?php

declare(strict_types=1);

namespace QuillStack\Mocks\ServerRequest;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;
use QuillStack\DI\Container;
use QuillStack\Http\Request\Factory\ServerRequest\RequestFromGlobalsFactory;
use QuillStack\Http\Stream\InputStream;
use QuillStack\Http\Uri\Factory\UriFactory;
use QuillStack\Mocks\AbstractMock;

final class MockBody extends AbstractMock
{
    public function __construct()
    {
        $container = new Container([
            StreamInterface::class => InputStream::class,
            InputStream::class => [
                'contest' => 'Body test.',
            ],
            UriFactoryInterface::class => UriFactory::class,
            RequestFromGlobalsFactory::class => [
                'server' => MockProtocolVersion::SERVER,
            ],
        ]);
        $factory = $container->get(RequestFromGlobalsFactory::class);
        $this->request = $factory->createServerRequest();
    }
}
