<?php

declare(strict_types=1);

namespace Tests\Fixtures\BenChallis\SqlMigrations;

use BenChallis\SqlMigrations\Migration\Revision\Revision;
use Tests\Fixtures\BenChallis\SqlMigrations\Revision\Revision20181121094934CreateATable;
use Tests\Fixtures\BenChallis\SqlMigrations\Revision\RevisionNotMatchingExpectedClassNamingConvention;

final class RevisionFixtures
{
    private function __construct()
    {
    }

    public static function dummy(): Revision
    {
        return new Revision20181121094934CreateATable();
    }

    public static function notMatchingExpectedNamingConvention(): Revision
    {
        return new RevisionNotMatchingExpectedClassNamingConvention();
    }
}
