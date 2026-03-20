<?php

namespace LaminasTest\Db\Adapter\Driver\Sqlsrv;

use Laminas\Db\Adapter\Driver\Sqlsrv\Connection;
use Laminas\Db\Adapter\Driver\Sqlsrv\Result;
use Laminas\Db\Adapter\Driver\Sqlsrv\Sqlsrv;
use Laminas\Db\Adapter\Driver\Sqlsrv\Statement;
use PHPUnit\Framework\TestCase;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Sqlsrv\Sqlsrv::class, 'registerConnection')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Sqlsrv\Sqlsrv::class, 'registerStatementPrototype')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Sqlsrv\Sqlsrv::class, 'registerResultPrototype')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Sqlsrv\Sqlsrv::class, 'getDatabasePlatformName')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Sqlsrv\Sqlsrv::class, 'getConnection')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Sqlsrv\Sqlsrv::class, 'createStatement')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Sqlsrv\Sqlsrv::class, 'createResult')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Sqlsrv\Sqlsrv::class, 'getPrepareType')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Sqlsrv\Sqlsrv::class, 'formatParameterName')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Sqlsrv\Sqlsrv::class, 'getLastGeneratedValue')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Sqlsrv\Sqlsrv::class, 'getResultPrototype')]
class SqlsrvTest extends TestCase
{
    /** @var Sqlsrv */
    protected $sqlsrv;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->sqlsrv = new Sqlsrv([]);
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
        $mockConnection->expects($this->once())->method('setDriver')->with($this->equalTo($this->sqlsrv));
        self::assertSame($this->sqlsrv, $this->sqlsrv->registerConnection($mockConnection));
    }

    public function testRegisterStatementPrototype()
    {
        $this->sqlsrv  = new Sqlsrv([]);
        $mockStatement = $this->getMockForAbstractClass(
            Statement::class,
            [],
            '',
            true,
            true,
            true,
            ['setDriver']
        );
        $mockStatement->expects($this->once())->method('setDriver')->with($this->equalTo($this->sqlsrv));
        self::assertSame($this->sqlsrv, $this->sqlsrv->registerStatementPrototype($mockStatement));
    }

    public function testRegisterResultPrototype()
    {
        $this->sqlsrv  = new Sqlsrv([]);
        $mockStatement = $this->getMockForAbstractClass(
            Result::class,
            [],
            '',
            true,
            true,
            true,
            ['setDriver']
        );
        self::assertSame($this->sqlsrv, $this->sqlsrv->registerResultPrototype($mockStatement));
    }

    public function testGetDatabasePlatformName()
    {
        $this->sqlsrv = new Sqlsrv([]);
        self::assertEquals('SqlServer', $this->sqlsrv->getDatabasePlatformName());
        self::assertEquals('SQLServer', $this->sqlsrv->getDatabasePlatformName(Sqlsrv::NAME_FORMAT_NATURAL));
    }

    #[\PHPUnit\Framework\Attributes\Depends('testRegisterConnection')]
    public function testGetConnection()
    {
        $conn = new Connection([]);
        $this->sqlsrv->registerConnection($conn);
        self::assertSame($conn, $this->sqlsrv->getConnection());
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
        $resultPrototype = $this->sqlsrv->getResultPrototype();

        self::assertInstanceOf(Result::class, $resultPrototype);
    }
}
