<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration;

use BenChallis\SqlMigrations\Migration\Metadata\State;
use Psl\Collection\MapInterface;
use Psl\Collection\MutableMap;
use Psl\Collection\Vector;
use Psl\Collection\VectorInterface;
use Psl\Dict;

final class InMemoryMigrations implements Migrations
{
    /**
     * @var MutableMap<int, Migration>
     */
    private readonly MutableMap $map;

    /**
     * @param MapInterface<int, Migration> $map
     */
    public function __construct(MapInterface $map)
    {
        $this->map = new MutableMap(Dict\sort_by_key($map->toArray()));
    }

    public function getAll(): VectorInterface
    {
        return new Vector($this->map->toArray());
    }

    public function get(Version $version): Migration
    {
        return $this->map->get($version->toInteger())
            ?? throw MigrationDoesNotExist::forVersion($version);
    }

    public function changeState(Version $version, State $state): Migration
    {
        $migration = $this->get($version)->withState($state);
        $this->map->set($version->toInteger(), $migration);

        return $migration;
    }
}
