<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Exceptions;

use Happenv\FilamentAccessControl\Support\PermissionWriter;
use RuntimeException;
use Throwable;

/**
 * Thrown by a {@see PermissionWriter} that will not write a list — from
 * {@see PermissionWriter::ensureMayWrite()}, inside its transaction, so nothing is written. The
 * screens show it to the operator instead of failing: the message, worded for them, is the
 * notification's title — or `title` and `body` when there is more to say.
 */
class PermissionWriteRefused extends RuntimeException
{
    public function __construct(
        string $message,
        protected ?string $title = null,
        protected ?string $body = null,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    public function title(): string
    {
        return $this->title ?? $this->getMessage();
    }

    public function body(): ?string
    {
        return $this->body;
    }
}
