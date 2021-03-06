<?php

declare(strict_types=1);

namespace QuillStack\Mocks\ServerRequest;

use QuillStack\Mocks\AbstractMock;

final class MockRootPath extends AbstractMock
{
    public const SERVER = [
        'REQUEST_METHOD' => 'POST',
        'HTTP_HOST' => '127.0.0.1',
        'REQUEST_URI' => '/',
        'SERVER_PROTOCOL' => '1.2',
    ];
}
