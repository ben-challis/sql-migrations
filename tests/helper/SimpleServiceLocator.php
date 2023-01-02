<?php

declare(strict_types=1);

namespace Tests\Helper\BenChallis\SqlMigrations;

use Psl\Collection\Map;
use Psl\Collection\MapInterface;
use Psr\Container\ContainerInterface;

final class SimpleServiceLocator implements ContainerInterface
{
    /**
     * @var MapInterface<string, mixed>
     */
    private readonly MapInterface $services;

    /**
     * @param array<string, mixed> $services
     */
    public function __construct(array $services)
    {
        $this->services = new Map($services);
    }

    public function get(string $id)
    {
        if (!$this->has($id)) {
            throw new ServiceNotFound();
        }

        return $this->services->get($id);
    }

    public function has(string $id): bool
    {
        return $this->services->contains($id);
    }
}
