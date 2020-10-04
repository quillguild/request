<?php

declare(strict_types=1);

namespace QuillStack\Http\Request\ServerRequest;

use PHPUnit\Framework\TestCase;
use QuillStack\Http\Request\ServerRequest;
use QuillStack\Mocks\ServerRequest\MockUploadedFiles;

final class UploadedFilesTest extends TestCase
{
    private ServerRequest $request;

    protected function setUp(): void
    {
        $this->request = (new MockUploadedFiles())->get();
    }

    public function testGetUploadedFiles()
    {
        $uploadedFiles = $this->request->getUploadedFiles();

        $this->assertCount(1, $uploadedFiles);
        $this->assertArrayHasKey('file', $uploadedFiles);
        $this->assertEquals('file1.jpg', $uploadedFiles['file']);
    }

    public function testWithUploadedFiles()
    {
        $request = $this->request->withUploadedFiles(['new-file' => 'file2.jpg']);

        $this->assertEquals(['file' => 'file1.jpg'], $this->request->getUploadedFiles());
        $this->assertEquals(['new-file' => 'file2.jpg'], $request->getUploadedFiles());
    }
}
