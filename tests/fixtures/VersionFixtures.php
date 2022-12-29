<?php

declare(strict_types=1);

namespace Tests\Fixtures\BenChallis\SqlMigrations;

use BenChallis\SqlMigrations\Migration\Version;
use Psl\Math;
use Psl\PseudoRandom;

final class VersionFixtures
{
    private function __construct()
    {
    }

    public static function random(): Version
    {
        $randomInt = PseudoRandom\int(1, Math\INT64_MAX);
        /** @var int<1, max> $randomInt */

        return Version::fromInteger($randomInt);
    }
}
