<?php

namespace LaminasTest\Db\Sql\Ddl\Column;

use Laminas\Db\Sql\Ddl\Column\Datetime;
use PHPUnit\Framework\TestCase;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Column\Datetime::class, 'getExpressionData')]
class DatetimeTest extends TestCase
{
    public function testGetExpressionData()
    {
        $column = new Datetime('foo');
        self::assertEquals(
            [['%s %s NOT NULL', ['foo', 'DATETIME'], [$column::TYPE_IDENTIFIER, $column::TYPE_LITERAL]]],
            $column->getExpressionData()
        );
    }
}
