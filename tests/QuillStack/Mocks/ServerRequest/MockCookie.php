<?php

declare(strict_types=1);

namespace QuillStack\Mocks\ServerRequest;

use QuillStack\Mocks\AbstractMock;

final class MockCookie extends AbstractMock
{
    public const COOKIE = [
        'cookie' => 'test',
    ];
}
