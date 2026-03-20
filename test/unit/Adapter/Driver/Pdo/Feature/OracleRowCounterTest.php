<?php

namespace LaminasTest\Db\Adapter\Driver\Pdo\Feature;

use Closure;
use Laminas\Db\Adapter\Driver\ConnectionInterface;
use Laminas\Db\Adapter\Driver\Pdo\Feature\OracleRowCounter;
use Laminas\Db\Adapter\Driver\Pdo\Pdo;
use Laminas\Db\Adapter\Driver\Pdo\Statement;
use Laminas\Db\Adapter\Driver\ResultInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Pdo\Feature\OracleRowCounter::class, 'getName')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Pdo\Feature\OracleRowCounter::class, 'getCountForStatement')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Pdo\Feature\OracleRowCounter::class, 'getCountForSql')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Pdo\Feature\OracleRowCounter::class, 'getRowCountClosure')]
class OracleRowCounterTest extends TestCase
{
    /** @var OracleRowCounter */
    protected $rowCounter;

    protected function setUp(): void
    {
        $this->rowCounter = new OracleRowCounter();
    }

    public function testGetName()
    {
        self::assertEquals('OracleRowCounter', $this->rowCounter->getName());
    }

    public function testGetCountForStatement()
    {
        $statement = $this->getMockStatement('SELECT XXX', 5);
        $statement->expects($this->once())->method('prepare')
            ->with($this->equalTo('SELECT COUNT(*) as "count" FROM (SELECT XXX)'));

        $count = $this->rowCounter->getCountForStatement($statement);
        self::assertEquals(5, $count);
    }

    public function testGetCountForSql()
    {
        $this->rowCounter->setDriver($this->getMockDriver(5));
        $count = $this->rowCounter->getCountForSql('SELECT XXX');
        self::assertEquals(5, $count);
    }

    public function testGetRowCountClosure()
    {
        $stmt = $this->getMockStatement('SELECT XXX', 5);

        /** @var Closure $closure */
        $closure = $this->rowCounter->getRowCountClosure($stmt);
        self::assertInstanceOf('Closure', $closure);
        self::assertEquals(5, $closure());
    }

    /**
     * @param mixed $returnValue
     * @return Statement&MockObject
     */
    protected function getMockStatement(string $sql, $returnValue)
    {
        /** @var Statement|MockObject $statement */
        $statement = $this->getMockBuilder(Statement::class)
            ->disableOriginalConstructor()
            ->getMock();

        // mock PDOStatement with stdClass
        $resource = $this->getMockBuilder('stdClass')
            ->getMock();
        $resource->expects($this->once())
            ->method('fetch')
            ->willReturn(['count' => $returnValue]);

        // mock the result
        $result = $this->getMockBuilder(ResultInterface::class)->getMock();
        $result->expects($this->once())
            ->method('getResource')
            ->willReturn($resource);

        $statement->setSql($sql);
        $statement->expects($this->once())
            ->method('execute')
            ->willReturn($result);

        return $statement;
    }

    /**
     * @param mixed $returnValue
     * @return Pdo&MockObject
     */
    protected function getMockDriver($returnValue)
    {
        $pdoStatement = $this->getMockBuilder('stdClass')
            ->disableOriginalConstructor()
            ->getMock(); // stdClass can be used here
        $pdoStatement->expects($this->once())
            ->method('fetch')
            ->willReturn(['count' => $returnValue]);

        $pdoConnection = $this->getMockBuilder('stdClass')
            ->getMock();
        $pdoConnection->expects($this->once())
            ->method('query')
            ->willReturn($pdoStatement);

        $connection = $this->getMockBuilder(ConnectionInterface::class)->getMock();
        $connection->expects($this->once())
            ->method('getResource')
            ->willReturn($pdoConnection);

        $driver = $this->getMockBuilder(Pdo::class)
            ->disableOriginalConstructor()
            ->getMock();
        $driver->expects($this->once())
            ->method('getConnection')
            ->willReturn($connection);

        return $driver;
    }
}
