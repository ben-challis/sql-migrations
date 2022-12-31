<?php

declare(strict_types=1);

namespace Tests\Helper\BenChallis\SqlMigrations;

use Psl\Iter;

final class IterableHelper
{
    private function __construct()
    {
    }

    /**
     * @template T
     *
     * @param iterable<T> $input
     *
     * @return list<T>
     */
    public static function toList(iterable $input): array
    {
        return \iterator_to_array(Iter\to_iterator($input), false);
    }
}
