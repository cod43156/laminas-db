<?php

namespace LaminasTest\Db\Metadata\Source;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Platform\PlatformInterface;
use Laminas\Db\Metadata\MetadataInterface;
use Laminas\Db\Metadata\Source\Factory;
use Laminas\Db\Metadata\Source\MysqlMetadata;
use Laminas\Db\Metadata\Source\OracleMetadata;
use Laminas\Db\Metadata\Source\PostgresqlMetadata;
use Laminas\Db\Metadata\Source\SqliteMetadata;
use Laminas\Db\Metadata\Source\SqlServerMetadata;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @param string $expectedReturnClass
     */
    #[DataProvider('validAdapterProvider')]
    public function testCreateSourceFromAdapter(string $adapter, $expectedReturnClass)
    {
        $source = Factory::createSourceFromAdapter($this->createAdapter($adapter));

        self::assertInstanceOf(MetadataInterface::class, $source);
        self::assertInstanceOf($expectedReturnClass, $source);
    }

    /** @psalm-return array<string, array{0: Adapter&MockObject, 1: MetadataInterface}> */
    public static function validAdapterProvider(): array
    {
        return [
            // Description => [adapter, expected return class]
            'MySQL'      => ['MySQL', MysqlMetadata::class],
            'SQLServer'  => ['SQLServer', SqlServerMetadata::class],
            'SQLite'     => ['SQLite', SqliteMetadata::class],
            'PostgreSQL' => ['PostgreSQL', PostgresqlMetadata::class],
            'Oracle'     => ['Oracle', OracleMetadata::class],
        ];
    }

    /**
     * @return (Adapter&MockObject)|MockObject
     */
    private function createAdapter(string $platformName)
    {
        $platform = $this->getMockBuilder(PlatformInterface::class)->getMock();
        $platform
            ->expects($this->any())
            ->method('getName')
            ->willReturn($platformName);

        $adapter = $this->getMockBuilder(Adapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $adapter
            ->expects($this->any())
            ->method('getPlatform')
            ->willReturn($platform);

        return $adapter;
    }
}
