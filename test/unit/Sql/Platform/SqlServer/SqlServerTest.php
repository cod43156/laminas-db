<?php

namespace LaminasTest\Db\Sql\Platform\SqlServer;

use Laminas\Db\Sql\Platform\SqlServer\SelectDecorator;
use Laminas\Db\Sql\Platform\SqlServer\SqlServer;
use Laminas\Db\Sql\Select;
use PHPUnit\Framework\TestCase;

use function current;
use function key;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Platform\SqlServer\SqlServer::class, '__construct')]
class SqlServerTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\TestDox('unit test / object test: Test SqlServer object has Select proxy')]
    public function testConstruct()
    {
        $sqlServer  = new SqlServer();
        $decorators = $sqlServer->getDecorators();

        $type      = key($decorators);
        $decorator = current($decorators);
        self::assertEquals(Select::class, $type);
        self::assertInstanceOf(SelectDecorator::class, $decorator);
    }
}
