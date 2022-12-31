<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration;

use BenChallis\SqlMigrations\Migration\Metadata\State;
use Psl\Collection\VectorInterface;

interface Migrations
{
    /**
     * @return VectorInterface<Migration>
     */
    public function getAll(): VectorInterface;

    /**
     * @throws MigrationDoesNotExist
     */
    public function get(Version $version): Migration;

    /**
     * @throws MigrationDoesNotExist
     */
    public function changeState(Version $version, State $state): Migration;
}
