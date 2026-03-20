<?php

namespace LaminasTest\Db\Sql\Ddl\Column;

use Laminas\Db\Sql\Ddl\Column\BigInteger;
use PHPUnit\Framework\TestCase;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Column\BigInteger::class, '__construct')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Column\Column::class, 'getExpressionData')]
class BigIntegerTest extends TestCase
{
    public function testObjectConstruction()
    {
        $integer = new BigInteger('foo');
        self::assertEquals('foo', $integer->getName());
    }

    public function testGetExpressionData()
    {
        $column = new BigInteger('foo');
        self::assertEquals(
            [['%s %s NOT NULL', ['foo', 'BIGINT'], [$column::TYPE_IDENTIFIER, $column::TYPE_LITERAL]]],
            $column->getExpressionData()
        );
    }
}
