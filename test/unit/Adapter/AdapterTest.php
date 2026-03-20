<?php

namespace LaminasTest\Db\Adapter;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\ConnectionInterface;
use Laminas\Db\Adapter\Driver\DriverInterface;
use Laminas\Db\Adapter\Driver\Mysqli\Mysqli;
use Laminas\Db\Adapter\Driver\Pdo\Pdo;
use Laminas\Db\Adapter\Driver\Pgsql\Pgsql;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\Adapter\Driver\Sqlsrv\Sqlsrv;
use Laminas\Db\Adapter\Driver\StatementInterface;
use Laminas\Db\Adapter\ParameterContainer;
use Laminas\Db\Adapter\Platform\IbmDb2;
use Laminas\Db\Adapter\Platform\Mysql;
use Laminas\Db\Adapter\Platform\Oracle;
use Laminas\Db\Adapter\Platform\PlatformInterface;
use Laminas\Db\Adapter\Platform\Postgresql;
use Laminas\Db\Adapter\Platform\Sql92;
use Laminas\Db\Adapter\Platform\Sqlite;
use Laminas\Db\Adapter\Platform\SqlServer;
use Laminas\Db\Adapter\Profiler;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\ResultSet\ResultSetInterface;
use LaminasTest\Db\TestAsset\TemporaryResultSet;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function extension_loaded;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Adapter::class, 'setProfiler')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Adapter::class, 'getProfiler')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Adapter::class, 'createDriver')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Adapter::class, 'createPlatform')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Adapter::class, 'getDriver')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Adapter::class, 'getPlatform')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Adapter::class, 'getQueryResultSetPrototype')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Adapter::class, 'getCurrentSchema')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Adapter::class, 'query')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Adapter::class, 'createStatement')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Adapter::class, '__get')]
class AdapterTest extends TestCase
{
    /** @var MockObject&DriverInterface */
    protected $mockDriver;

    /** @var MockObject&PlatformInterface */
    protected $mockPlatform;

    /** @var MockObject&ConnectionInterface */
    protected $mockConnection;

    /** @var MockObject&StatementInterface */
    protected $mockStatement;

    /** @var Adapter */
    protected $adapter;

    protected function setUp(): void
    {
        $this->mockDriver     = $this->createMock(DriverInterface::class);
        $this->mockConnection = $this->createMock(ConnectionInterface::class);
        $this->mockDriver->method('checkEnvironment')->will($this->returnValue(true));
        $this->mockDriver->method('getConnection')
            ->will($this->returnValue($this->mockConnection));
        $this->mockPlatform  = $this->createMock(PlatformInterface::class);
        $this->mockStatement = $this->createMock(StatementInterface::class);
        $this->mockDriver->method('createStatement')
            ->will($this->returnValue($this->mockStatement));

        $this->adapter = new Adapter($this->mockDriver, $this->mockPlatform);
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test setProfiler() will store profiler')]
    public function testSetProfiler()
    {
        $ret = $this->adapter->setProfiler(new Profiler\Profiler());
        self::assertSame($this->adapter, $ret);
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test getProfiler() will store profiler')]
    public function testGetProfiler()
    {
        $this->adapter->setProfiler($profiler = new Profiler\Profiler());
        self::assertSame($profiler, $this->adapter->getProfiler());

        $adapter = new Adapter(['driver' => $this->mockDriver, 'profiler' => true], $this->mockPlatform);
        self::assertInstanceOf(\Laminas\Db\Adapter\Profiler\Profiler::class, $adapter->getProfiler());
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test createDriverFromParameters() will create proper driver type')]
    public function testCreateDriver()
    {
        if (extension_loaded('mysqli')) {
            $adapter = new Adapter(['driver' => 'mysqli'], $this->mockPlatform);
            self::assertInstanceOf(Mysqli::class, $adapter->driver);
            unset($adapter);
        }

        if (extension_loaded('pgsql')) {
            $adapter = new Adapter(['driver' => 'pgsql'], $this->mockPlatform);
            self::assertInstanceOf(Pgsql::class, $adapter->driver);
            unset($adapter);
        }

        if (extension_loaded('sqlsrv')) {
            $adapter = new Adapter(['driver' => 'sqlsrv'], $this->mockPlatform);
            self::assertInstanceOf(Sqlsrv::class, $adapter->driver);
            unset($adapter);
        }

        if (extension_loaded('pdo')) {
            $adapter = new Adapter(['driver' => 'pdo_sqlite'], $this->mockPlatform);
            self::assertInstanceOf(Pdo::class, $adapter->driver);
            unset($adapter);
        }
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test createPlatformFromDriver() will create proper platform from driver')]
    public function testCreatePlatform()
    {
        $driver = clone $this->mockDriver;
        $driver->expects($this->any())->method('getDatabasePlatformName')->will($this->returnValue('Mysql'));
        $adapter = new Adapter($driver);
        self::assertInstanceOf(Mysql::class, $adapter->platform);
        unset($adapter, $driver);

        $driver = clone $this->mockDriver;
        $driver->expects($this->any())->method('getDatabasePlatformName')->will($this->returnValue('SqlServer'));
        $adapter = new Adapter($driver);
        self::assertInstanceOf(SqlServer::class, $adapter->platform);
        unset($adapter, $driver);

        $driver = clone $this->mockDriver;
        $driver->expects($this->any())->method('getDatabasePlatformName')->will($this->returnValue('Postgresql'));
        $adapter = new Adapter($driver);
        self::assertInstanceOf(Postgresql::class, $adapter->platform);
        unset($adapter, $driver);

        $driver = clone $this->mockDriver;
        $driver->expects($this->any())->method('getDatabasePlatformName')->will($this->returnValue('Sqlite'));
        $adapter = new Adapter($driver);
        self::assertInstanceOf(Sqlite::class, $adapter->platform);
        unset($adapter, $driver);

        $driver = clone $this->mockDriver;
        $driver->expects($this->any())->method('getDatabasePlatformName')->will($this->returnValue('IbmDb2'));
        $adapter = new Adapter($driver);
        self::assertInstanceOf(IbmDb2::class, $adapter->platform);
        unset($adapter, $driver);

        $driver = clone $this->mockDriver;
        $driver->expects($this->any())->method('getDatabasePlatformName')->will($this->returnValue('Oracle'));
        $adapter = new Adapter($driver);
        self::assertInstanceOf(Oracle::class, $adapter->platform);
        unset($adapter, $driver);

        $driver = clone $this->mockDriver;
        $driver->expects($this->any())->method('getDatabasePlatformName')->will($this->returnValue('Foo'));
        $adapter = new Adapter($driver);
        self::assertInstanceOf(Sql92::class, $adapter->platform);
        unset($adapter, $driver);

        // ensure platform can created via string, and also that it passed in options to platform object
        $driver  = [
            'driver'           => 'pdo_oci',
            'platform'         => 'Oracle',
            'platform_options' => ['quote_identifiers' => false],
        ];
        $adapter = new Adapter($driver);
        self::assertInstanceOf(Oracle::class, $adapter->platform);
        self::assertEquals('foo', $adapter->getPlatform()->quoteIdentifier('foo'));
        unset($adapter, $driver);
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test getDriver() will return driver object')]
    public function testGetDriver()
    {
        self::assertSame($this->mockDriver, $this->adapter->getDriver());
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test getPlatform() returns platform object')]
    public function testGetPlatform()
    {
        self::assertSame($this->mockPlatform, $this->adapter->getPlatform());
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test getPlatform() returns platform object')]
    public function testGetQueryResultSetPrototype()
    {
        self::assertInstanceOf(ResultSetInterface::class, $this->adapter->getQueryResultSetPrototype());
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test getCurrentSchema() returns current schema from connection object')]
    public function testGetCurrentSchema()
    {
        $this->mockConnection->expects($this->any())->method('getCurrentSchema')->will($this->returnValue('FooSchema'));
        self::assertEquals('FooSchema', $this->adapter->getCurrentSchema());
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test query() in prepare mode produces a statement object')]
    public function testQueryWhenPreparedProducesStatement()
    {
        $s = $this->adapter->query('SELECT foo');
        self::assertSame($this->mockStatement, $s);
    }

    #[\PHPUnit\Framework\Attributes\Group('#210')]
    public function testProducedResultSetPrototypeIsDifferentForEachQuery()
    {
        $statement = $this->createMock(StatementInterface::class);
        $result    = $this->createMock(ResultInterface::class);

        $this->mockDriver->method('createStatement')
            ->willReturn($statement);
        $this->mockStatement->method('execute')
            ->willReturn($result);
        $result->method('isQueryResult')
            ->willReturn(true);

        self::assertNotSame(
            $this->adapter->query('SELECT foo', []),
            $this->adapter->query('SELECT foo', [])
        );
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test query() in prepare mode, with array of parameters, produces a result object')]
    public function testQueryWhenPreparedWithParameterArrayProducesResult()
    {
        $parray    = ['bar' => 'foo'];
        $sql       = 'SELECT foo, :bar';
        $statement = $this->getMockBuilder(StatementInterface::class)->getMock();
        $result    = $this->getMockBuilder(ResultInterface::class)->getMock();
        $this->mockDriver->expects($this->any())->method('createStatement')
            ->with($sql)->will($this->returnValue($statement));
        $this->mockStatement->expects($this->any())->method('execute')->will($this->returnValue($result));

        $r = $this->adapter->query($sql, $parray);
        self::assertSame($result, $r);
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test query() in prepare mode, with ParameterContainer, produces a result object')]
    public function testQueryWhenPreparedWithParameterContainerProducesResult()
    {
        $sql                = 'SELECT foo';
        $parameterContainer = $this->getMockBuilder(ParameterContainer::class)->getMock();
        $result             = $this->getMockBuilder(ResultInterface::class)->getMock();
        $this->mockDriver->expects($this->any())->method('createStatement')
            ->with($sql)->will($this->returnValue($this->mockStatement));
        $this->mockStatement->expects($this->any())->method('execute')->will($this->returnValue($result));
        $result->expects($this->any())->method('isQueryResult')->will($this->returnValue(true));

        $r = $this->adapter->query($sql, $parameterContainer);
        self::assertInstanceOf(ResultSet::class, $r);
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test query() in execute mode produces a driver result object')]
    public function testQueryWhenExecutedProducesAResult()
    {
        $sql    = 'SELECT foo';
        $result = $this->getMockBuilder(ResultInterface::class)->getMock();
        $this->mockConnection->expects($this->any())->method('execute')->with($sql)->will($this->returnValue($result));

        $r = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        self::assertSame($result, $r);
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test query() in execute mode produces a resultset object')]
    public function testQueryWhenExecutedProducesAResultSetObjectWhenResultIsQuery()
    {
        $sql = 'SELECT foo';

        $result = $this->getMockBuilder(ResultInterface::class)->getMock();
        $this->mockConnection->expects($this->any())->method('execute')->with($sql)->will($this->returnValue($result));
        $result->expects($this->any())->method('isQueryResult')->will($this->returnValue(true));

        $r = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        self::assertInstanceOf(ResultSet::class, $r);

        $r = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE, new TemporaryResultSet());
        self::assertInstanceOf(TemporaryResultSet::class, $r);
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test createStatement() produces a statement object')]
    public function testCreateStatement()
    {
        self::assertSame($this->mockStatement, $this->adapter->createStatement());
    }

    // @codingStandardsIgnoreStart
    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test __get() works')]
    public function test__get()
    {
        // @codingStandardsIgnoreEnd
        self::assertSame($this->mockDriver, $this->adapter->driver);
        self::assertSame($this->mockDriver, $this->adapter->DrivER);
        self::assertSame($this->mockPlatform, $this->adapter->PlatForm);
        self::assertSame($this->mockPlatform, $this->adapter->platform);

        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Invalid magic');
        $this->adapter->foo;
    }
}
