<?php

namespace LaminasTest\Db\Adapter\Driver\Sqlsrv;

use Laminas\Db\Adapter\Driver\Sqlsrv\Connection;
use Laminas\Db\Adapter\Driver\Sqlsrv\Result;
use Laminas\Db\Adapter\Driver\Sqlsrv\Sqlsrv;
use Laminas\Db\Adapter\Driver\Sqlsrv\Statement;

use function sqlsrv_connect;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Sqlsrv\Connection::class, 'getCurrentSchema')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Sqlsrv\Connection::class, 'setResource')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Sqlsrv\Connection::class, 'getResource')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Sqlsrv\Connection::class, 'connect')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Sqlsrv\Connection::class, 'isConnected')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Sqlsrv\Connection::class, 'disconnect')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Sqlsrv\Connection::class, 'beginTransaction')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Sqlsrv\Connection::class, 'commit')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Sqlsrv\Connection::class, 'rollback')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Sqlsrv\Connection::class, 'execute')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Sqlsrv\Connection::class, 'prepare')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Sqlsrv\Connection::class, 'getLastGeneratedValue')]
#[\PHPUnit\Framework\Attributes\Group('integration')]
#[\PHPUnit\Framework\Attributes\Group('integration-sqlserver')]
class ConnectionIntegrationTest extends AbstractIntegrationTest
{
    public function testGetCurrentSchema()
    {
        $connection = new Connection($this->variables);
        self::assertIsString($connection->getCurrentSchema());
    }

    public function testSetResource()
    {
        $resource   = sqlsrv_connect(
            $this->variables['hostname'],
            [
                'UID'                    => $this->variables['username'],
                'PWD'                    => $this->variables['password'],
                'TrustServerCertificate' => 1,
            ]
        );
        $connection = new Connection([]);
        self::assertSame($connection, $connection->setResource($resource));

        $connection->disconnect();
        unset($connection);
        unset($resource);
    }

    public function testGetResource()
    {
        $connection = new Connection($this->variables);
        $connection->connect();
        self::assertIsResource($connection->getResource());

        $connection->disconnect();
        unset($connection);
    }

    public function testConnect()
    {
        $connection = new Connection($this->variables);
        self::assertSame($connection, $connection->connect());
        self::assertTrue($connection->isConnected());

        $connection->disconnect();
        unset($connection);
    }

    public function testIsConnected()
    {
        $connection = new Connection($this->variables);
        self::assertFalse($connection->isConnected());
        self::assertSame($connection, $connection->connect());
        self::assertTrue($connection->isConnected());

        $connection->disconnect();
        unset($connection);
    }

    public function testDisconnect()
    {
        $connection = new Connection($this->variables);
        $connection->connect();
        self::assertTrue($connection->isConnected());
        $connection->disconnect();
        self::assertFalse($connection->isConnected());
    }

    /**
     * @todo   Implement testBeginTransaction().
     */
    public function testBeginTransaction()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @todo   Implement testCommit().
     */
    public function testCommit()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @todo   Implement testRollback().
     */
    public function testRollback()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testExecute()
    {
        $sqlsrv     = new Sqlsrv($this->variables);
        $connection = $sqlsrv->getConnection();

        $result = $connection->execute('SELECT \'foo\'');
        self::assertInstanceOf(Result::class, $result);
    }

    public function testPrepare()
    {
        $sqlsrv     = new Sqlsrv($this->variables);
        $connection = $sqlsrv->getConnection();

        $statement = $connection->prepare('SELECT \'foo\'');
        self::assertInstanceOf(Statement::class, $statement);
    }

    public function testGetLastGeneratedValue()
    {
        $this->markTestIncomplete('Need to create a temporary sequence.');
        $connection = new Connection($this->variables);
        $connection->getLastGeneratedValue();
    }

    #[\PHPUnit\Framework\Attributes\Group('laminas3469')]
    public function testConnectReturnsConnectionWhenResourceSet()
    {
        $resource   = sqlsrv_connect(
            $this->variables['hostname'],
            [
                'UID'                    => $this->variables['username'],
                'PWD'                    => $this->variables['password'],
                'TrustServerCertificate' => 1,
            ]
        );
        $connection = new Connection([]);
        $connection->setResource($resource);
        self::assertSame($connection, $connection->connect());

        $connection->disconnect();
        unset($connection);
        unset($resource);
    }
}
