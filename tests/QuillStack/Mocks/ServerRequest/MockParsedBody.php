<?php

declare(strict_types=1);

namespace QuillStack\Mocks\ServerRequest;

use QuillStack\Mocks\AbstractMock;

final class MockParsedBody extends AbstractMock
{
    public const POST = [
        'form' => 'input',
    ];
}
