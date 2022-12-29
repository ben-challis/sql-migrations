<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration;

final class MigrationStuck extends \RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function for(Migration $migration): self
    {
        return new self(
            \sprintf(
                'Migration version "%d" is stuck in the applying state and needs manual intervention.',
                $migration->metadata->version->toInteger(),
            ),
        );
    }
}
