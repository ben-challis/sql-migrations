<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Revision;

use Amp\Sql\Executor;
use Amp\Sql\Result;
use Amp\Sql\Statement;

/**
 * A revision to a database schema.
 */
interface Revision
{
    /**
     * Apply the changes required for this schema revision.
     *
     * @template TResult of Result
     * @template TStatement of Statement
     *
     * @param Executor<TResult, TStatement> $executor
     */
    public function apply(Executor $executor): void;

    /**
     * Revert the changes of this schema revision.
     *
     * @template TResult of Result
     * @template TStatement of Statement
     *
     * @param Executor<TResult, TStatement> $executor
     *
     * @throws IrrevertibleRevision If this revision cannot be logically reverted.
     */
    public function revert(Executor $executor): void;
}
