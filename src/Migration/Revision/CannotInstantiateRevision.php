<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Revision;

final class CannotInstantiateRevision extends \RuntimeException
{
    private function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, previous: $previous);
    }

    /**
     * @param class-string<Revision> $class
     */
    public static function forClass(string $class, ?\Throwable $previous = null): self
    {
        return new self(\sprintf('Cannot instantiate revision "%s".', $class), $previous);
    }
}
