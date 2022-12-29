<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Metadata;

use BenChallis\SqlMigrations\Migration\Version;

final class MetadataDoesNotExist extends \RuntimeException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function forVersion(Version $version): self
    {
        return new self(\sprintf('Metadata for revision "%d" does not exist.', $version->toInteger()));
    }
}
