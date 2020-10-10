<?php

declare(strict_types=1);

namespace QuillStack\Http\Request\ServerRequest;

use PHPUnit\Framework\TestCase;
use QuillStack\Http\Request\Request;
use QuillStack\Mocks\ServerRequest\MockQuery;

final class QueryParamsTest extends TestCase
{
    private Request $request;

    protected function setUp(): void
    {
        $this->request = (new MockQuery())->get();
    }

    public function testGetQueryParams()
    {
        $queryParams = $this->request->getQueryParams();

        $this->assertCount(1, $queryParams);
        $this->assertArrayHasKey('query', $queryParams);
        $this->assertEquals('value', $queryParams['query']);
    }

    public function testWithCookieParams()
    {
        $request = $this->request->withQueryParams(['more' => 'no']);

        $this->assertEquals(['query' => 'value'], $this->request->getQueryParams());
        $this->assertEquals(['more' => 'no'], $request->getQueryParams());
    }
}
