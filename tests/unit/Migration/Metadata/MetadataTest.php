<?php

declare(strict_types=1);

namespace Tests\Unit\BenChallis\SqlMigrations\Migration\Metadata;

use BenChallis\SqlMigrations\Migration\Metadata\CannotTransitionState;
use BenChallis\SqlMigrations\Migration\Metadata\Metadata;
use BenChallis\SqlMigrations\Migration\Metadata\State;
use BenChallis\SqlMigrations\Migration\Version;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\BenChallis\SqlMigrations\StateFixtures;
use Tests\Fixtures\BenChallis\SqlMigrations\VersionFixtures;

/**
 * @covers \BenChallis\SqlMigrations\Migration\Metadata\Metadata
 */
final class MetadataTest extends TestCase
{
    /**
     * @test
     */
    public function can_transition_to_a_new_state(): void
    {
        $metadata = Metadata::with(Version::fromInteger(2022_05_01_01_01_01), State::Unapplied);
        $transitioned = $metadata->withState(State::Applied);

        self::assertNotSame($metadata, $transitioned);
        self::assertSame(State::Unapplied, $metadata->state);
        self::assertSame(State::Applied, $transitioned->state);
    }

    /**
     * @test
     */
    public function exposes_values(): void
    {
        $metadata = Metadata::with(Version::fromInteger(2022_05_01_01_01_01), State::Unapplied);

        self::assertTrue($metadata->version->equals(Version::fromInteger(2022_05_01_01_01_01)));
        self::assertSame(State::Unapplied, $metadata->state);
    }

    /**
     * @return iterable<array{State, State}>
     */
    public function provideDisallowedStateTransitions(): iterable
    {
        yield from StateFixtures::disallowedTransitions();
    }

    /**
     * @test
     * @dataProvider provideDisallowedStateTransitions
     */
    public function throws_if_transitioning_to_disallowed_state(State $from, State $to): void
    {
        $metadata = Metadata::with(
            VersionFixtures::random(),
            $from,
        );

        $this->expectException(CannotTransitionState::class);

        $metadata->withState($to);
    }
}
