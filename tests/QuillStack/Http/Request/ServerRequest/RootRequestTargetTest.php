<?php

declare(strict_types=1);

namespace QuillStack\Http\Request\ServerRequest;

use PHPUnit\Framework\TestCase;
use QuillStack\Http\Request\ServerRequest;
use QuillStack\Mocks\ServerRequest\MockRootPath;

final class RootRequestTargetTest extends TestCase
{
    private ServerRequest $request;

    protected function setUp(): void
    {
        $this->request = (new MockRootPath())->get();
    }

    public function testGetRequestTarget()
    {
        $this->assertEquals('/', $this->request->getRequestTarget());
    }

    public function testWithRequestTarget()
    {
        $request = $this->request->withRequestTarget('/new-target');

        $this->assertEquals('/', $this->request->getRequestTarget());
        $this->assertEquals('/new-target', $request->getRequestTarget());
    }
}
