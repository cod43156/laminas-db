<?php

namespace LaminasTest\Db\Sql\Platform\Sqlite;

use Laminas\Db\Sql\Platform\Sqlite\SelectDecorator;
use Laminas\Db\Sql\Platform\Sqlite\Sqlite;
use Laminas\Db\Sql\Select;
use PHPUnit\Framework\TestCase;

use function current;
use function key;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Platform\Sqlite\Sqlite::class, '__construct')]
class SqliteTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\TestDox('unit test / object test: Test Sqlite constructor will register the decorator')]
    public function testConstructorRegistersSqliteDecorator()
    {
        $mysql      = new Sqlite();
        $decorators = $mysql->getDecorators();

        $type      = key($decorators);
        $decorator = current($decorators);
        self::assertEquals(Select::class, $type);
        self::assertInstanceOf(SelectDecorator::class, $decorator);
    }
}
