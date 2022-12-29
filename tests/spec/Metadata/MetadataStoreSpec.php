<?php

declare(strict_types=1);

namespace Tests\Spec\BenChallis\SqlMigrations\Metadata;

use Amp\PHPUnit\AsyncTestCase;
use BenChallis\SqlMigrations\Migration\Metadata\Metadata;
use BenChallis\SqlMigrations\Migration\Metadata\MetadataStore;
use BenChallis\SqlMigrations\Migration\Metadata\State;
use BenChallis\SqlMigrations\Migration\Version;
use Psl\Collection\Vector;
use Psl\Collection\VectorInterface;
use Tests\Helper\BenChallis\SqlMigrations\MetadataAsserter;

abstract class MetadataStoreSpec extends AsyncTestCase
{
    abstract protected function createStore(): MetadataStore;

    /**
     * @return VectorInterface<Metadata>
     */
    final protected function expectedMetadata(): VectorInterface
    {
        return new Vector([
            Metadata::with(
                Version::fromInteger(1),
                State::Applied,
            ),
            Metadata::with(
                Version::fromInteger(2),
                State::Applied,
            ),
            Metadata::with(
                Version::fromInteger(3),
                State::Applied,
            ),
            Metadata::with(
                Version::fromInteger(4),
                State::Applied,
            ),
        ]);
    }

    /**
     * @test
     */
    final public function save_and_fetch_afterwards(): void
    {
        $store = $this->createStore();
        $version = Version::fromInteger(5);

        $store->save(Metadata::with($version, State::Applying));
        $metadata = $store->fetch($version);

        MetadataAsserter::assertThat($metadata)
            ->hasVersion($version)
            ->hasState(State::Applying);
    }
}
