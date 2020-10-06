<?php

declare(strict_types=1);

namespace QuillStack\Http\Request\ServerRequest;

use PHPUnit\Framework\TestCase;
use QuillStack\Http\Request\ServerRequest;
use QuillStack\Mocks\ServerRequest\MockParsedBody;

final class ParsedBodyParamsTest extends TestCase
{
    private ServerRequest $request;

    protected function setUp(): void
    {
        $this->request = (new MockParsedBody())->get();
    }

    public function testGetParsedBody()
    {
        $parsedBody = $this->request->getParsedBody();

        $this->assertCount(1, $parsedBody);
        $this->assertArrayHasKey('form', $parsedBody);
        $this->assertEquals('input', $parsedBody['form']);
    }

    public function testWithUploadedFiles()
    {
        $request = $this->request->withParsedBody(['new-form' => 'select']);

        $this->assertEquals(['form' => 'input'], $this->request->getParsedBody());
        $this->assertEquals(['new-form' => 'select'], $request->getParsedBody());
    }
}
