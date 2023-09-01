<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration;

use BenChallis\SqlMigrations\Migration\Metadata\Metadata;
use BenChallis\SqlMigrations\Migration\Metadata\MetadataStore;
use BenChallis\SqlMigrations\Migration\Metadata\State;
use Psl\Collection\VectorInterface;

/**
 * Decorator that updates a {@see MetadataStore} when state changes are performed.
 */
final readonly class MetadataUpdatingMigrations implements Migrations
{
    public function __construct(
        private Migrations $migrations,
        private MetadataStore $metadata,
    ) {
    }

    public function getAll(): VectorInterface
    {
        return $this->migrations->getAll();
    }

    public function get(Version $version): Migration
    {
        return $this->migrations->get($version);
    }

    public function changeState(Version $version, State $state): Migration
    {
        $migration = $this->migrations->changeState($version, $state);
        $this->metadata->save(Metadata::with($version, $state));

        return $migration;
    }
}
