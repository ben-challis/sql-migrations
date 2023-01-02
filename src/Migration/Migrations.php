<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration;

use BenChallis\SqlMigrations\Migration\Metadata\CannotTransitionState;
use BenChallis\SqlMigrations\Migration\Metadata\State;
use Psl\Collection\VectorInterface;

/**
 * The central access point into available migrations.
 */
interface Migrations
{
    /**
     * Gets all migrations, in ascending order of version.
     *
     * @return VectorInterface<Migration>
     */
    public function getAll(): VectorInterface;

    /**
     * Gets a specific migration by version.
     *
     * @throws MigrationDoesNotExist If the migration does not exist.
     */
    public function get(Version $version): Migration;

    /**
     * Changes the state of a migration.
     *
     * @throws MigrationDoesNotExist If the migration does not exist.
     * @throws CannotTransitionState If the migration cannot be changed into the provided state.
     */
    public function changeState(Version $version, State $state): Migration;
}
