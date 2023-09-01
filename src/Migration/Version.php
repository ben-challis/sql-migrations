<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration;

/**
 * A migration's schema version.
 *
 * The schema versions are treated as being in ascending order, but not sequential.
 */
final readonly class Version
{
    /**
     * @param positive-int $value
     */
    private function __construct(private int $value)
    {
    }

    /**
     * @param positive-int $value
     */
    public static function fromInteger(int $value): self
    {
        return new self($value);
    }

    /**
     * @return positive-int
     */
    public function toInteger(): int
    {
        return $this->value;
    }

    public function equals(self $candidate): bool
    {
        return $this->value === $candidate->value;
    }

    public function isAfter(self $candidate): bool
    {
        return $this->value > $candidate->value;
    }

    public function isBefore(self $candidate): bool
    {
        return $this->value < $candidate->value;
    }

    public function compareTo(self $candidate): int
    {
        return $this->value <=> $candidate->value;
    }
}
