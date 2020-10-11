<?php

declare(strict_types=1);

namespace QuillStack\Http\Request\ServerRequest;

use PHPUnit\Framework\TestCase;
use QuillStack\Http\Request\Request;
use QuillStack\Http\Stream\InputStream;
use QuillStack\Mocks\ServerRequest\MockProtocolVersion;
use QuillStack\Mocks\ServerRequest\MockUri;

final class HeaderBagTest extends TestCase
{
    private Request $request;

    protected function setUp(): void
    {
        $this->request = (new MockProtocolVersion())->get();
    }

    public function testWithHeader()
    {
        $request = $this->request;
        $this->assertEquals([], $request->getHeader('new'));

        $request = $this->request->withHeader('new', 'header');

        $this->assertEquals([], $this->request->getHeader('new'));
        $this->assertEquals(['header'], $request->getHeader('new'));
    }

    public function testWithAddedHeader()
    {
        $request = $this->request;
        $this->assertEquals([], $request->getHeader('new'));

        $request = $this->request->withAddedHeader('new', 'header');
        $request = $request->withAddedHeader('new', 'added');

        $this->assertEquals([], $this->request->getHeader('new'));
        $this->assertEquals(['header', 'added'], $request->getHeader('new'));
    }

    public function testWithoutAddedHeader()
    {
        $request = $this->request;
        $this->assertEquals(['localhost:8000'], $request->getHeader('host'));

        $request = $this->request->withoutHeader('Host');

        $this->assertEquals(['localhost:8000'], $this->request->getHeader('host'));
        $this->assertEquals([], $request->getHeader('host'));
    }
}
