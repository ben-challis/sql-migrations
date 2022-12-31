<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Metadata;

enum State: string
{
    case Unapplied = 'unapplied';
    case Applying = 'applying';
    case Applied = 'applied';

    public function canTransitionTo(self $to): bool
    {
        return !($this === self::Applied && $to === self::Applying);
    }

    /**
     * @throws CannotTransitionState
     */
    public function transitionTo(self $to): self
    {
        if (!$this->canTransitionTo($to)) {
            throw new CannotTransitionState($this, $to);
        }

        return $to;
    }
}
