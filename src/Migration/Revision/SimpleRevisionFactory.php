<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Revision;

/**
 * Just instantiates the revision, assuming a zero-argument constructor exists.
 */
final class SimpleRevisionFactory implements RevisionFactory
{
    public function create(string $revisionClass): Revision
    {
        return new $revisionClass();
    }
}
