<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Metadata;

use Amp\Sql\Executor;
use Amp\Sql\Result;
use Amp\Sql\Statement;

/**
 * @template TResult of Result
 * @template TStatement of Statement
 */
interface SchemaManager
{
    /**
     * @param Executor<TResult, TStatement> $executor
     */
    public function isUpToDate(Executor $executor): bool;

    /**
     * @param Executor<TResult, TStatement> $executor
     */
    public function updateIfRequired(Executor $executor): void;
}
