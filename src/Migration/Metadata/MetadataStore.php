<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Metadata;

use BenChallis\SqlMigrations\Migration\Version;
use Psl\Collection\MapInterface;

/**
 * A store of migration metadata, usually persisted.
 */
interface MetadataStore
{
    /**
     * Saves a migration's metadata.
     */
    public function save(Metadata $metadata): void;

    /**
     * Fetches a migration's metadata by version.
     *
     * @throws MetadataDoesNotExist If the metadata does not exist in the store.
     */
    public function fetch(Version $version): Metadata;

    /**
     * Fetches all migration metadata from the store.
     *
     * @return MapInterface<int, Metadata> A map of metadata keyed by version.
     */
    public function fetchAll(): MapInterface;
}
