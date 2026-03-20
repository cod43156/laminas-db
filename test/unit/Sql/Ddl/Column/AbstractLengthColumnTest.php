<?php

namespace LaminasTest\Db\Sql\Ddl\Column;

use Laminas\Db\Sql\Ddl\Column\AbstractLengthColumn;
use PHPUnit\Framework\TestCase;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Column\AbstractLengthColumn::class, 'setLength')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Column\AbstractLengthColumn::class, 'getLength')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Column\AbstractLengthColumn::class, 'getExpressionData')]
class AbstractLengthColumnTest extends TestCase
{
    public function testSetLength()
    {
        $column = $this->getMockForAbstractClass(AbstractLengthColumn::class, ['foo', 55]);
        self::assertEquals(55, $column->getLength());
        self::assertSame($column, $column->setLength(20));
        self::assertEquals(20, $column->getLength());
    }

    public function testGetLength()
    {
        $column = $this->getMockForAbstractClass(AbstractLengthColumn::class, ['foo', 55]);
        self::assertEquals(55, $column->getLength());
    }

    public function testGetExpressionData()
    {
        $column = $this->getMockForAbstractClass(AbstractLengthColumn::class, ['foo', 4]);

        self::assertEquals(
            [['%s %s NOT NULL', ['foo', 'INTEGER(4)'], [$column::TYPE_IDENTIFIER, $column::TYPE_LITERAL]]],
            $column->getExpressionData()
        );
    }
}
