<?php

declare(strict_types=1);

namespace QuillStack\Http\Request\ServerRequest;

use PHPUnit\Framework\TestCase;
use QuillStack\Http\Request\Factory\Exceptions\RequestMethodNotKnownException;
use QuillStack\Http\Request\Request;
use QuillStack\Mocks\ServerRequest\MockProtocolVersion;

final class MethodTest extends TestCase
{
    private Request $request;

    protected function setUp(): void
    {
        $this->request = (new MockProtocolVersion())->get();
    }

    public function testGetMethod()
    {
        $this->assertEquals('GET', $this->request->getMethod());
    }

    public function testWithMethod()
    {
        $request = $this->request->withMethod('POST');

        $this->assertEquals('GET', $this->request->getMethod());
        $this->assertEquals('POST', $request->getMethod());
    }

    public function testWithNonExistingMethod()
    {
        $this->expectException(RequestMethodNotKnownException::class);

        $this->request->withMethod('SMURFS');
    }
}
