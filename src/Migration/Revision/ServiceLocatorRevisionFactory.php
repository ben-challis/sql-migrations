<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Revision;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

final readonly class ServiceLocatorRevisionFactory implements RevisionFactory
{
    public function __construct(private ContainerInterface $revisions)
    {
    }

    public function create(string $revisionClass): Revision
    {
        try {
            $revision = $this->revisions->get($revisionClass);
        } catch (ContainerExceptionInterface $exception) {
            throw CannotInstantiateRevision::forClass($revisionClass, $exception);
        }

        if (!$revision instanceof $revisionClass) {
            throw CannotInstantiateRevision::forClass(
                $revisionClass,
                new \RuntimeException(
                    \sprintf(
                        'Expected instance of "%s" from the service locator, "%s" provided.',
                        $revisionClass,
                        \get_debug_type($revision),
                    ),
                ),
            );
        }

        return $revision;
    }
}
