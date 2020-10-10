<?php

declare(strict_types=1);

namespace QuillStack\Http\Request;

use PHPUnit\Framework\TestCase;
use QuillStack\Mocks\ServerRequest\MockProtocolVersion;

final class AttributesTest extends TestCase
{
    private Request $request;

    protected function setUp(): void
    {
        $this->request = (new MockProtocolVersion())->get();
    }

    public function testGetEmptyAttributes()
    {
        $this->assertEquals([], $this->request->getAttributes());
    }

    public function testWithAttribute()
    {
        $attributes = $this->request->withAttribute('name', 'value');

        $this->assertEquals([], $this->request->getAttributes());
        $this->assertNull($this->request->getAttribute('name'));
        $this->assertEquals(['name' => 'value'], $attributes->getAttributes());
        $this->assertEquals('value', $attributes->getAttribute('name'));
    }

    public function testWithoutAttribute()
    {
        $attributes = $this->request->withAttribute('name', 'value');
        $attributes = $attributes->withoutAttribute('name');
        $attributes = $attributes->withoutAttribute('name-not-exists');

        $this->assertEquals([], $attributes->getAttributes());
        $this->assertNull($attributes->getAttribute('name'));
    }
}
