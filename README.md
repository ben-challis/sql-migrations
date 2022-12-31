# SQL Migrations

**Work in progress**

An SQL database migrations library based on [AMP](https://amphp.org). Currently only supports MySQL.

```php
use Amp\Mysql\MysqlConfig;
use Amp\Sync\LocalMutex;
use Amp\Sync\Mutex;
use BenChallis\SqlMigrations\ClassDiscovery\Composer\AutoloaderClassDiscovererFactory;
use BenChallis\SqlMigrations\Migration\Discovery\RevisionClassDiscoverer;
use BenChallis\SqlMigrations\Migration\Discovery\RevisionDiscoverer;
use BenChallis\SqlMigrations\Migration\Metadata\MySql\MySqlMetadataStore;
use BenChallis\SqlMigrations\Migration\Metadata\MySql\MySqlSchemaManager;
use BenChallis\SqlMigrations\Migration\Metadata\SchemaUpdatingMetadataStore;
use BenChallis\SqlMigrations\Migration\MigrationCollector;
use BenChallis\SqlMigrations\Migration\MigrationsFactory;
use BenChallis\SqlMigrations\Migration\Migrator;
use BenChallis\SqlMigrations\Migration\Revision\SimpleRevisionFactory;
use function Amp\Mysql\connect;

$connection = connect(MysqlConfig::fromAuthority(DB_HOST, DB_USER, DB_PASS, DB_DATABASE));
$metadata = new SchemaUpdatingMetadataStore(
    new MySqlSchemaManager(DB_DATABASE), 
    $connection, 
    new MySqlMetadataStore($connection)
);

$migrations = MigrationsFactory::create(
    $metadata,
    new MigrationCollector(
        $metadata, 
        new RevisionDiscoverer(
            new RevisionClassDiscoverer(
                (new AutoloaderClassDiscovererFactory())->fromVendorDirectory(__DIR__.'/vendor')
            ),
            new SimpleRevisionFactory(),
        )
    )
);

$migrator = new Migrator($migrations, $connection);

$migrator->migrate(new LocalMutex()); // You'll want to use a distributed (i.e. Redis) lock instead here.
```
