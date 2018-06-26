<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Exception;

use InvalidArgumentException as BaseInvalidArgumentException;
use Throwable;

final class InvalidArgumentException extends BaseInvalidArgumentException implements Exception
{
    /**
     * Creates a new invalid argument exception.
     */
    public function __construct(string $argument, string $whatIsWrong, ?Throwable $previous = null)
    {
        parent::__construct(
            sprintf(
                'Argument "%s" has an invalid value. %s',
                $argument,
                $whatIsWrong
            ),
            0,
            $previous
        );
    }
}
