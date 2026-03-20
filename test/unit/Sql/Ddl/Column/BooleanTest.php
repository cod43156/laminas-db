<?php

namespace LaminasTest\Db\Sql\Ddl\Column;

use Laminas\Db\Sql\Ddl\Column\Boolean;
use PHPUnit\Framework\TestCase;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Column\Boolean::class, 'getExpressionData')]
#[\PHPUnit\Framework\Attributes\CoversClass(\Laminas\Db\Sql\Ddl\Column\Boolean::class)]
class BooleanTest extends TestCase
{
    public function testGetExpressionData()
    {
        $column = new Boolean('foo');
        self::assertEquals(
            [['%s %s NOT NULL', ['foo', 'BOOLEAN'], [$column::TYPE_IDENTIFIER, $column::TYPE_LITERAL]]],
            $column->getExpressionData()
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('6257')]
    public function testIsAlwaysNotNullable()
    {
        $column = new Boolean('foo', true);

        self::assertFalse($column->isNullable());

        $column->setNullable(true);

        self::assertFalse($column->isNullable());
    }
}
