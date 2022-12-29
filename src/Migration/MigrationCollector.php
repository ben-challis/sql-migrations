<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration;

use BenChallis\SqlMigrations\ClassDiscovery\PhpNamespace;
use BenChallis\SqlMigrations\Migration\Discovery\RevisionDiscoverer;
use BenChallis\SqlMigrations\Migration\Metadata\Metadata;
use BenChallis\SqlMigrations\Migration\Metadata\MetadataStore;
use BenChallis\SqlMigrations\Migration\Metadata\State;

final class MigrationCollector
{
    public function __construct(
        private readonly MetadataStore $metadata,
        private readonly RevisionDiscoverer $revisionDiscoverer,
        private readonly VersionExtractor $versionExtractor,
    ) {
    }

    /**
     * @return array<positive-int, Migration>
     */
    public function collect(PhpNamespace ...$namespaces): array
    {
        $metadata = $this->metadata->fetchAll();
        $revisions = $this->revisionDiscoverer->discover(...$namespaces);

        $map = [];

        foreach ($revisions as $revision) {
            $version = $this->versionExtractor->fromInstance($revision);

            $map[$version->toInteger()] = Migration::with(
                $revision,
                $metadata[$version->toInteger()] ?? Metadata::with($version, State::Unapplied),
            );
        }

        return $map;
    }
}
