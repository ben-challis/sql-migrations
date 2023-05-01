<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration;

use BenChallis\SqlMigrations\Migration\Metadata\State;
use Psl\Collection\VectorInterface;

/**
 * Defers loading migrations until interaction.
 */
final class LazyLoadingMigrations implements Migrations
{
    /**
     * @var \Closure(): void|null
     */
    private ?\Closure $loader;

    private ?Migrations $migrations = null;

    /**
     * @param \Closure(): Migrations $loader
     */
    public function __construct(\Closure $loader)
    {
        $this->loader = function () use ($loader): void {
            $this->migrations = $loader->bindTo(null)();
            $this->loader = null;
        };
    }

    public function getAll(): VectorInterface
    {
        if ($this->loader !== null) {
            ($this->loader)();
        }

        \assert($this->migrations instanceof Migrations);

        return $this->migrations->getAll();
    }

    public function get(Version $version): Migration
    {
        if ($this->loader !== null) {
            ($this->loader)();
        }

        \assert($this->migrations instanceof Migrations);

        return $this->migrations->get($version);
    }

    public function changeState(Version $version, State $state): Migration
    {
        if ($this->loader !== null) {
            ($this->loader)();
        }

        \assert($this->migrations instanceof Migrations);

        return $this->migrations->changeState($version, $state);
    }
}
