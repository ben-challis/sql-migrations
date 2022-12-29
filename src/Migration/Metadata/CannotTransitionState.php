<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Metadata;

final class CannotTransitionState extends \RuntimeException
{
    public function __construct(public readonly State $from, public readonly State $to)
    {
        parent::__construct(\sprintf('Cannot transition from state "%s" to "%s".', $from->value, $to->value));
    }
}
