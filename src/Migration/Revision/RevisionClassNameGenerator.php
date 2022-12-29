<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Revision;

use Psl\Regex;
use Psr\Clock\ClockInterface;

final class RevisionClassNameGenerator
{
    public function __construct(private readonly ClockInterface $clock)
    {
    }

    /**
     *
     * @param non-empty-string $description
     *
     * @return non-empty-string
     */
    public function generate(string $description): string
    {
        if (!Regex\matches($description, '~^[a-zA-Z]+$~')) {
            throw new \InvalidArgumentException('Description must be a string of just characters a-Z.');
        }

        return \sprintf('Revision%d%s', $this->clock->now()->format('YmdHis'), $description);
    }
}
