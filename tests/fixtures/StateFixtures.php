<?php

declare(strict_types=1);

namespace Tests\Fixtures\BenChallis\SqlMigrations;

use BenChallis\SqlMigrations\Migration\Metadata\State;

final class StateFixtures
{
    private function __construct()
    {
    }

    /**
     * @return iterable<array{State, State}>
     */
    public static function allowedTransitions(): iterable
    {
        yield [State::Unapplied, State::Applying];
        yield [State::Applying, State::Applied];
        yield [State::Applying, State::Unapplied];
        yield [State::Applied, State::Unapplied];
    }

    /**
     * @return iterable<array{State, State}>
     */
    public static function disallowedTransitions(): iterable
    {
        yield [State::Applied, State::Applying];
    }
}
