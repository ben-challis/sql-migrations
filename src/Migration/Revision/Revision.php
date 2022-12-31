<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Revision;

use Amp\Sql\Executor;
use Amp\Sql\Result;
use Amp\Sql\Statement;

interface Revision
{
    /**
     * @template TResult of Result
     * @template TStatement of Statement
     *
     * @param Executor<TResult, TStatement> $executor
     */
    public function apply(Executor $executor): void;

    /**
     * @template TResult of Result
     * @template TStatement of Statement
     *
     * @param Executor<TResult, TStatement> $executor
     *
     * @throws IrrevertibleRevision
     */
    public function revert(Executor $executor): void;
}
