<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Revision;

use Psr\Container\ContainerInterface;

final class ServiceLocatorRevisionFactory implements RevisionFactory
{
    public function __construct(private readonly ContainerInterface $revisions)
    {
    }

    public function create(string $revisionClass): Revision
    {
        $revision = $this->revisions->get($revisionClass);
        \assert($revision instanceof $revisionClass);

        return $revision;
    }
}
