<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Metadata;

use BenChallis\SqlMigrations\Migration\Version;

final readonly class Metadata
{
    private function __construct(public Version $version, public State $state)
    {
    }

    public static function with(Version $version, State $state): self
    {
        return new self($version, $state);
    }

    /**
     * @throws CannotTransitionState
     */
    public function withState(State $state): self
    {
        return new self($this->version, $this->state->transitionTo($state));
    }

    public function equals(self $other): bool
    {
        return $this->version->equals($other->version)
            && $this->state === $other->state;
    }
}
