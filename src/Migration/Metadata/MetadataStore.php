<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Metadata;

use BenChallis\SqlMigrations\Migration\Version;
use Psl\Collection\MapInterface;

interface MetadataStore
{
    public function save(Metadata $metadata): void;

    /**
     * @throws MetadataDoesNotExist
     */
    public function fetch(Version $version): Metadata;

    /**
     * @return MapInterface<int, Metadata>
     */
    public function fetchAll(): MapInterface;
}
