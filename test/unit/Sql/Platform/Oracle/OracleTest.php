<?php

namespace LaminasTest\Db\Sql\Platform\Oracle;

use Laminas\Db\Sql\Platform\Oracle\Oracle;
use Laminas\Db\Sql\Platform\Oracle\SelectDecorator;
use Laminas\Db\Sql\Select;
use PHPUnit\Framework\TestCase;

use function current;
use function key;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Platform\Oracle\Oracle::class, '__construct')]
class OracleTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\TestDox('unit test / object test: Test Mysql object has Select proxy')]
    public function testConstruct()
    {
        $oracle     = new Oracle();
        $decorators = $oracle->getDecorators();

        $type      = key($decorators);
        $decorator = current($decorators);
        self::assertEquals(Select::class, $type);
        self::assertInstanceOf(SelectDecorator::class, $decorator);
    }
}
