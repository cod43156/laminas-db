<?php

namespace LaminasTest\Db\Sql\Ddl\Column;

use Laminas\Db\Sql\Ddl\Column\Column;
use PHPUnit\Framework\TestCase;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Column\Column::class, 'setName')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Column\Column::class, 'getName')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Column\Column::class, 'setNullable')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Column\Column::class, 'isNullable')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Column\Column::class, 'setDefault')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Column\Column::class, 'getDefault')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Column\Column::class, 'setOptions')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Column\Column::class, 'setOption')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Column\Column::class, 'getOptions')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Column\Column::class, 'getExpressionData')]
class ColumnTest extends TestCase
{
    public function testSetName(): Column
    {
        $column = new Column();
        self::assertSame($column, $column->setName('foo'));
        return $column;
    }

    #[\PHPUnit\Framework\Attributes\Depends('testSetName')]
    public function testGetName(Column $column)
    {
        self::assertEquals('foo', $column->getName());
    }

    public function testSetNullable(): Column
    {
        $column = new Column();
        self::assertSame($column, $column->setNullable(true));
        return $column;
    }

    #[\PHPUnit\Framework\Attributes\Depends('testSetNullable')]
    public function testIsNullable(Column $column)
    {
        self::assertTrue($column->isNullable());
        $column->setNullable(false);
        self::assertFalse($column->isNullable());
    }

    public function testSetDefault(): Column
    {
        $column = new Column();
        self::assertSame($column, $column->setDefault('foo bar'));
        return $column;
    }

    #[\PHPUnit\Framework\Attributes\Depends('testSetDefault')]
    public function testGetDefault(Column $column)
    {
        self::assertEquals('foo bar', $column->getDefault());
    }

    public function testSetOptions(): Column
    {
        $column = new Column();
        self::assertSame($column, $column->setOptions(['autoincrement' => true]));
        return $column;
    }

    public function testSetOption()
    {
        $column = new Column();
        self::assertSame($column, $column->setOption('primary', true));
    }

    #[\PHPUnit\Framework\Attributes\Depends('testSetOptions')]
    public function testGetOptions(Column $column)
    {
        self::assertEquals(['autoincrement' => true], $column->getOptions());
    }

    public function testGetExpressionData()
    {
        $column = new Column();
        $column->setName('foo');
        self::assertEquals(
            [['%s %s NOT NULL', ['foo', 'INTEGER'], [$column::TYPE_IDENTIFIER, $column::TYPE_LITERAL]]],
            $column->getExpressionData()
        );

        $column->setNullable(true);
        self::assertEquals(
            [['%s %s', ['foo', 'INTEGER'], [$column::TYPE_IDENTIFIER, $column::TYPE_LITERAL]]],
            $column->getExpressionData()
        );

        $column->setDefault('bar');
        self::assertEquals(
            [
                [
                    '%s %s DEFAULT %s',
                    ['foo', 'INTEGER', 'bar'],
                    [$column::TYPE_IDENTIFIER, $column::TYPE_LITERAL, $column::TYPE_VALUE],
                ],
            ],
            $column->getExpressionData()
        );
    }
}
