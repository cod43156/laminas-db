<?php

namespace LaminasTest\Db\Adapter\Driver\Oci8;

use Laminas\Db\Adapter\Driver\Oci8\Connection;
use Laminas\Db\Adapter\Driver\Oci8\Oci8;
use Laminas\Db\Adapter\Driver\Oci8\Result;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Connection::class, 'getCurrentSchema')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Connection::class, 'setResource')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Connection::class, 'getResource')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Connection::class, 'connect')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Connection::class, 'isConnected')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Connection::class, 'disconnect')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Connection::class, 'beginTransaction')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Connection::class, 'commit')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Connection::class, 'rollback')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Connection::class, 'execute')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Connection::class, 'getLastGeneratedValue')]
#[\PHPUnit\Framework\Attributes\Group('integration')]
#[\PHPUnit\Framework\Attributes\Group('integration-oracle')]
class ConnectionIntegrationTest extends AbstractIntegrationTest
{
    public function testGetCurrentSchema()
    {
        $connection = new Connection($this->variables);
        self::assertInternalType('string', $connection->getCurrentSchema());
    }

    public function testSetResource()
    {
        $this->markTestIncomplete('edit this');
        $resource = oci_connect(
            $this->variables['username'],
            $this->variables['password'],
            $this->variables['hostname']
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
        self::assertInternalType('resource', $connection->getResource());

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
        $oci8       = new Oci8($this->variables);
        $connection = $oci8->getConnection();

        $result = $connection->execute('SELECT \'foo\' FROM DUAL');
        self::assertInstanceOf(Result::class, $result);
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
        $this->markTestIncomplete('edit this');
        $resource = oci_connect(
            $this->variables['username'],
            $this->variables['password'],
            $this->variables['hostname']
        );

        $connection = new Connection([]);
        $connection->setResource($resource);
        self::assertSame($connection, $connection->connect());

        $connection->disconnect();
        unset($connection);
        unset($resource);
    }
}
