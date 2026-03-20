<?php

namespace LaminasIntegrationTest\Db;

use LaminasIntegrationTest\Db\Platform\FixtureLoader;
use LaminasIntegrationTest\Db\Platform\MysqlFixtureLoader;
use LaminasIntegrationTest\Db\Platform\PgsqlFixtureLoader;
use LaminasIntegrationTest\Db\Platform\SqlServerFixtureLoader;
use PHPUnit\Event\TestSuite\Finished;
use PHPUnit\Event\TestSuite\FinishedSubscriber;
use PHPUnit\Event\TestSuite\Started;
use PHPUnit\Event\TestSuite\StartedSubscriber;
use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;

use function getenv;
use function printf;

class IntegrationTestListener implements Extension
{
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        /** @var FixtureLoader[] $fixtureLoaders */
        $fixtureLoaders = [];

        if (getenv('TESTS_LAMINAS_DB_ADAPTER_DRIVER_MYSQL')) {
            $fixtureLoaders[] = new MysqlFixtureLoader();
        }

        if (getenv('TESTS_LAMINAS_DB_ADAPTER_DRIVER_PGSQL')) {
            $fixtureLoaders[] = new PgsqlFixtureLoader();
        }

        if (getenv('TESTS_LAMINAS_DB_ADAPTER_DRIVER_SQLSRV')) {
            $fixtureLoaders[] = new SqlServerFixtureLoader();
        }

        if (empty($fixtureLoaders)) {
            return;
        }

        $facade->registerSubscribers(
            new readonly class ($fixtureLoaders) implements StartedSubscriber {
                /** @param FixtureLoader[] $fixtureLoaders */
                public function __construct(private array $fixtureLoaders)
                {
                }

                public function notify(Started $event): void
                {
                    if ($event->testSuite()->name() !== 'integration test') {
                        return;
                    }

                    printf("\nIntegration test started.\n");

                    foreach ($this->fixtureLoaders as $fixtureLoader) {
                        $fixtureLoader->createDatabase();
                    }
                }
            },
            new readonly class ($fixtureLoaders) implements FinishedSubscriber {
                /** @param FixtureLoader[] $fixtureLoaders */
                public function __construct(private array $fixtureLoaders)
                {
                }

                public function notify(Finished $event): void
                {
                    if ($event->testSuite()->name() !== 'integration test') {
                        return;
                    }

                    printf("\nIntegration test ended.\n");

                    foreach ($this->fixtureLoaders as $fixtureLoader) {
                        $fixtureLoader->dropDatabase();
                    }
                }
            },
        );
    }
}
