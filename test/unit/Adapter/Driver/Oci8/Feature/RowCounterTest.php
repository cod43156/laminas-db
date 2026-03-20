<?php

namespace LaminasTest\Db\Adapter\Driver\Oci8\Feature;

use Closure;
use Laminas\Db\Adapter\Driver\ConnectionInterface;
use Laminas\Db\Adapter\Driver\Oci8\Feature\RowCounter;
use Laminas\Db\Adapter\Driver\Oci8\Oci8;
use Laminas\Db\Adapter\Driver\Oci8\Statement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Feature\RowCounter::class, 'getName')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Feature\RowCounter::class, 'getCountForStatement')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Feature\RowCounter::class, 'getCountForSql')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Feature\RowCounter::class, 'getRowCountClosure')]
class RowCounterTest extends TestCase
{
    /** @var RowCounter */
    protected $rowCounter;

    protected function setUp(): void
    {
        $this->rowCounter = new RowCounter();
    }

    public function testGetName()
    {
        self::assertEquals('RowCounter', $this->rowCounter->getName());
    }

    public function testGetCountForStatement()
    {
        $statement = $this->getMockStatement('SELECT XXX', 5);
        $statement->expects($this->once())
            ->method('prepare')
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
     * @param string $sql
     * @param mixed $returnValue
     * @return Statement&MockObject
     */
    protected function getMockStatement($sql, $returnValue)
    {
        $statement = $this->getMockBuilder(Statement::class)
            ->disableOriginalConstructor()
            ->getMock();

        // mock the result
        $result = $this->getMockBuilder('stdClass')
            ->getMock();
        $result->expects($this->once())
            ->method('current')
            ->will($this->returnValue(['count' => $returnValue]));
        $statement->setSql($sql);
        $statement->expects($this->once())
            ->method('execute')
            ->will($this->returnValue($result));
        return $statement;
    }

    /**
     * @param mixed $returnValue
     * @return Oci8&MockObject
     */
    protected function getMockDriver($returnValue)
    {
        $oci8Statement = $this->getMockBuilder('stdClass')
            ->disableOriginalConstructor()
            ->getMock(); // stdClass can be used here
        $oci8Statement->expects($this->once())
            ->method('current')
            ->will($this->returnValue(['count' => $returnValue]));
        $connection = $this->getMockBuilder(ConnectionInterface::class)->getMock();
        $connection->expects($this->once())
            ->method('execute')
            ->will($this->returnValue($oci8Statement));
        $driver = $this->getMockBuilder(Oci8::class)
            ->disableOriginalConstructor()
            ->getMock();
        $driver->expects($this->once())
            ->method('getConnection')
            ->will($this->returnValue($connection));
        return $driver;
    }
}
