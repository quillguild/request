<?php

declare(strict_types=1);

namespace QuillStack\Mocks\ServerRequest;

use QuillStack\Mocks\AbstractMock;

final class MockQuery extends AbstractMock
{
    public const QUERY = [
        'query' => 'value',
    ];
}
