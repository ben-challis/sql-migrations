<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration;

use BenChallis\SqlMigrations\Migration\Revision\Revision;

interface VersionExtractor
{
    /**
     * @throws VersionCouldNotBeExtracted
     */
    public function fromInstance(Revision $revision): Version;
}
