<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration;

final class MigrationDoesNotExist extends \RuntimeException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function forVersion(Version $version): self
    {
        return new self(\sprintf('Migration for version "%d" does not exist.', $version->toInteger()));
    }
}
