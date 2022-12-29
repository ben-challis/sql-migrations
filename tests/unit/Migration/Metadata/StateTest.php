<?php

declare(strict_types=1);

namespace Tests\Unit\BenChallis\SqlMigrations\Migration\Metadata;

use BenChallis\SqlMigrations\Migration\Metadata\CannotTransitionState;
use BenChallis\SqlMigrations\Migration\Metadata\State;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\BenChallis\SqlMigrations\StateFixtures;

/**
 * @covers \BenChallis\SqlMigrations\Migration\Metadata\State
 * @covers \BenChallis\SqlMigrations\Migration\Metadata\CannotTransitionState
 */
final class StateTest extends TestCase
{
    /**
     * @return iterable<array{State, State}>
     */
    public static function provideAllowedTransitions(): iterable
    {
        yield from StateFixtures::allowedTransitions();
    }

    /**
     * @test
     * @dataProvider provideAllowedTransitions
     */
    public function can_transition_to_other_states(State $from, State $to): void
    {
        self::assertSame($to, $from->transitionTo($to));
    }

    /**
     * @return iterable<array{State, State}>
     */
    public static function provideDisallowedTransitions(): iterable
    {
        yield from StateFixtures::disallowedTransitions();
    }

    /**
     * @test
     * @dataProvider provideDisallowedTransitions
     */
    public function cannot_transition_to_disallowed_states(State $from, State $to): void
    {
        $this->expectException(CannotTransitionState::class);

        $from->transitionTo($to);
    }
}
