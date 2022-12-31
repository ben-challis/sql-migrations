<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Revision;

interface RevisionFactory
{
    /**
     * @template T of Revision
     *
     * @param class-string<T> $revisionClass
     *
     * @return T
     */
    public function create(string $revisionClass): Revision;
}
