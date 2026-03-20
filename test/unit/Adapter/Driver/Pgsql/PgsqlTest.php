<?php

namespace LaminasTest\Db\Adapter\Driver\Pgsql;

use Laminas\Db\Adapter\Driver\Pgsql\Connection;
use Laminas\Db\Adapter\Driver\Pgsql\Pgsql;
use Laminas\Db\Adapter\Driver\Pgsql\Result;
use Laminas\Db\Adapter\Driver\Pgsql\Statement;
use Laminas\Db\Adapter\Exception\RuntimeException;
use PHPUnit\Framework\TestCase;

use function extension_loaded;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Pgsql\Pgsql::class, 'checkEnvironment')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Pgsql\Pgsql::class, 'registerConnection')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Pgsql\Pgsql::class, 'registerStatementPrototype')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Pgsql\Pgsql::class, 'registerResultPrototype')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Pgsql\Pgsql::class, 'getDatabasePlatformName')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Pgsql\Pgsql::class, 'getConnection')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Pgsql\Pgsql::class, 'createStatement')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Pgsql\Pgsql::class, 'createResult')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Pgsql\Pgsql::class, 'getPrepareType')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Pgsql\Pgsql::class, 'formatParameterName')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Pgsql\Pgsql::class, 'getLastGeneratedValue')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Pgsql\Pgsql::class, 'getResultPrototype')]
class PgsqlTest extends TestCase
{
    /** @var Pgsql */
    protected $pgsql;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->pgsql = new Pgsql([]);
    }

    public function testCheckEnvironment()
    {
        if (! extension_loaded('pgsql')) {
            $this->expectException(RuntimeException::class);
        }
        $this->pgsql->checkEnvironment();
        self::assertTrue(true, 'No exception was thrown');
    }

    public function testRegisterConnection()
    {
        $mockConnection = $this->getMockForAbstractClass(
            Connection::class,
            [[]],
            '',
            true,
            true,
            true,
            ['setDriver']
        );
        $mockConnection->expects($this->once())->method('setDriver')->with($this->equalTo($this->pgsql));
        self::assertSame($this->pgsql, $this->pgsql->registerConnection($mockConnection));
    }

    public function testRegisterStatementPrototype()
    {
        $this->pgsql   = new Pgsql([]);
        $mockStatement = $this->getMockForAbstractClass(
            Statement::class,
            [],
            '',
            true,
            true,
            true,
            ['setDriver']
        );
        $mockStatement->expects($this->once())->method('setDriver')->with($this->equalTo($this->pgsql));
        self::assertSame($this->pgsql, $this->pgsql->registerStatementPrototype($mockStatement));
    }

    public function testRegisterResultPrototype()
    {
        $this->pgsql   = new Pgsql([]);
        $mockStatement = $this->getMockForAbstractClass(
            Result::class,
            [],
            '',
            true,
            true,
            true,
            ['setDriver']
        );
        self::assertSame($this->pgsql, $this->pgsql->registerResultPrototype($mockStatement));
    }

    public function testGetDatabasePlatformName()
    {
        $this->pgsql = new Pgsql([]);
        self::assertEquals('Postgresql', $this->pgsql->getDatabasePlatformName());
        self::assertEquals('PostgreSQL', $this->pgsql->getDatabasePlatformName(Pgsql::NAME_FORMAT_NATURAL));
    }

    #[\PHPUnit\Framework\Attributes\Depends('testRegisterConnection')]
    public function testGetConnection()
    {
        $conn = new Connection([]);
        $this->pgsql->registerConnection($conn);
        self::assertSame($conn, $this->pgsql->getConnection());
    }

    /**
     * @todo   Implement testGetPrepareType().
     */
    public function testCreateStatement()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @todo   Implement testGetPrepareType().
     */
    public function testCreateResult()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @todo   Implement testGetPrepareType().
     */
    public function testGetPrepareType()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @todo   Implement testFormatParameterName().
     */
    public function testFormatParameterName()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @todo   Implement testGetLastGeneratedValue().
     */
    public function testGetLastGeneratedValue()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGetResultPrototype()
    {
        $resultPrototype = $this->pgsql->getResultPrototype();

        self::assertInstanceOf(Result::class, $resultPrototype);
    }
}
