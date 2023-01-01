<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Discovery;

use BenChallis\SqlMigrations\ClassDiscovery\Composer\AutoloaderClassDiscovererFactory;
use BenChallis\SqlMigrations\ClassDiscovery\ReadableDirectory;
use BenChallis\SqlMigrations\Migration\Revision\RevisionFactory;
use BenChallis\SqlMigrations\Migration\Revision\SimpleRevisionFactory;

final class RevisionDiscovererFactory
{
    private function __construct()
    {
    }

    public static function create(
        ReadableDirectory $vendorDirectory,
        RevisionFactory $revisionFactory = new SimpleRevisionFactory(),
    ): RevisionDiscoverer {
        return new RevisionDiscoverer(
            new RevisionClassDiscoverer(
                (new AutoloaderClassDiscovererFactory())->fromVendorDirectory($vendorDirectory),
            ),
            $revisionFactory,
        );
    }
}
