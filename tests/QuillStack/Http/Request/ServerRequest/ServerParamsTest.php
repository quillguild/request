<?php

declare(strict_types=1);

namespace QuillStack\Http\Request\ServerRequest;

use PHPUnit\Framework\TestCase;
use QuillStack\Http\Request\Request;
use QuillStack\Mocks\ServerRequest\MockProtocolVersion;

final class ServerParamsTest extends TestCase
{
    private Request $request;

    protected function setUp(): void
    {
        $this->request = (new MockProtocolVersion())->get();
    }

    public function testGetServerParams()
    {
        $serverParams = $this->request->getServerParams();

        $this->assertCount(4, $serverParams);
        $this->assertArrayHasKey('REQUEST_METHOD', $serverParams);
        $this->assertEquals('GET', $serverParams['REQUEST_METHOD']);
    }
}
