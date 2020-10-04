<?php

declare(strict_types=1);

namespace QuillStack\Http\Request\ServerRequest;

use PHPUnit\Framework\TestCase;
use QuillStack\Http\Request\ServerRequest;
use QuillStack\Mocks\ServerRequest\MockCookie;

final class CookieParamsTest extends TestCase
{
    private ServerRequest $request;

    protected function setUp(): void
    {
        $this->request = (new MockCookie())->get();
    }

    public function testGetCookieParams()
    {
        $cookieParams = $this->request->getCookieParams();

        $this->assertCount(1, $cookieParams);
        $this->assertArrayHasKey('cookie', $cookieParams);
        $this->assertEquals('test', $cookieParams['cookie']);
    }

    public function testWithCookieParams()
    {
        $request = $this->request->withCookieParams(['new' => 'yes']);

        $this->assertEquals(['cookie' => 'test'], $this->request->getCookieParams());
        $this->assertEquals(['new' => 'yes'], $request->getCookieParams());
    }
}
