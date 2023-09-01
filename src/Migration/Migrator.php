<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration;

use Amp\Sql\Executor;
use Amp\Sql\Result;
use Amp\Sql\Statement;
use Amp\Sync\Mutex;
use BenChallis\SqlMigrations\Migration\Metadata\State;

final readonly class Migrator
{
    /**
     * @template TResult of Result
     * @template TStatement of Statement
     *
     * @param Executor<TResult, TStatement> $executor
     */
    public function __construct(private Migrations $migrations, private Executor $executor)
    {
    }

    /**
     * @param Mutex $mutex A mutex to ensure that migrations cannot be run concurrently and race condition
     * against each other.
     *
     * @throws MigrationStuck If a migration is stuck in a state which requires manual intervention
     */
    public function migrate(Mutex $mutex): void
    {
        $lock = $mutex->acquire();

        try {
            $migrations = $this->migrations->getAll()
                ->filter(
                    static fn (Migration $migration): bool => $migration->metadata->state !== State::Applied
                    && $migration->metadata->state === State::Applying ? throw MigrationStuck::for($migration) : true,
                );

            foreach ($migrations as $migration) {
                $migration = $this->migrations->changeState($migration->metadata->version, State::Applying);

                $migration->revision->apply($this->executor);

                $this->migrations->changeState($migration->metadata->version, State::Applied);
            }
        } finally {
            $lock->release();
        }
    }
}
