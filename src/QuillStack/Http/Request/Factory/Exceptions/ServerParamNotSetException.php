<?php

declare(strict_types=1);

namespace QuillStack\Http\Request\Factory\Exceptions;

use QuillStack\ValidationExceptionInterface;
use RuntimeException;

final class ServerParamNotSetException extends RuntimeException implements ValidationExceptionInterface
{
}
