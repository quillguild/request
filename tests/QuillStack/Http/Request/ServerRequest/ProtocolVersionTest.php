<?php

declare(strict_types=1);

namespace QuillStack\Http\Request\ServerRequest;

use PHPUnit\Framework\TestCase;
use QuillStack\DI\Container;
use QuillStack\Http\Request\Factory\ServerRequest\RequestFromGlobalsFactory;
use QuillStack\Http\Request\ServerRequest;

final class ProtocolVersionTest extends TestCase
{
    private ServerRequest $request;

    protected function setUp(): void
    {
        $container = new Container([
            RequestFromGlobalsFactory::class => [
                'server' => [
                    'REQUEST_METHOD' => 'GET',
                    'HTTP_HOST' => 'localhost:8000',
                    'REQUEST_URI' => '/',
                    'SERVER_PROTOCOL' => '1.1',
                ],
            ],
        ]);
        $factory = $container->get(RequestFromGlobalsFactory::class);
        $this->request = $factory->createServerRequest();
    }

    public function testGetProtocolVersion()
    {
        $this->assertEquals('1.1', $this->request->getProtocolVersion());
    }

    public function testWithProtocolVersion()
    {
        $request = $this->request->withProtocolVersion('1.2');

        $this->assertEquals('1.1', $this->request->getProtocolVersion());
        $this->assertEquals('1.2', $request->getProtocolVersion());
    }
}
