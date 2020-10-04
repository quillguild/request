<?php

declare(strict_types=1);

namespace QuillStack\Mocks\ServerRequest;

use QuillStack\Mocks\AbstractMock;

final class MockUploadedFiles extends AbstractMock
{
    public const FILES = [
        'file' => 'file1.jpg',
    ];
}
