<?php

namespace LaminasTest\Db\Sql\Ddl\Column;

use Laminas\Db\Sql\Ddl\Column\Time;
use PHPUnit\Framework\TestCase;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Column\Time::class, 'getExpressionData')]
class TimeTest extends TestCase
{
    public function testGetExpressionData()
    {
        $column = new Time('foo');
        self::assertEquals(
            [['%s %s NOT NULL', ['foo', 'TIME'], [$column::TYPE_IDENTIFIER, $column::TYPE_LITERAL]]],
            $column->getExpressionData()
        );
    }
}
