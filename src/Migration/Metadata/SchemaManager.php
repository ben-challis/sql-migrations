<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Metadata;

use Amp\Sql\Executor;
use Amp\Sql\Result;
use Amp\Sql\Statement;

/**
 * Manages the schema of the migration metadata within a persistence context.
 *
 * @template TResult of Result
 * @template TStatement of Statement
 */
interface SchemaManager
{
    /**
     * Tests if the metadata schema is currently up-to-date and ready to use.
     *
     * @param Executor<TResult, TStatement> $executor
     */
    public function isUpToDate(Executor $executor): bool;

    /**
     * Updates the metadata schema (or does nothing if not required) to the latest version.
     *
     * @param Executor<TResult, TStatement> $executor
     */
    public function updateIfRequired(Executor $executor): void;
}
