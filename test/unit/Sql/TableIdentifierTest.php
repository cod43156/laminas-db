<?php

namespace LaminasTest\Db\Sql;

use Laminas\Db\Sql\Exception\InvalidArgumentException;
use Laminas\Db\Sql\TableIdentifier;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

use function array_merge;

/**
 * Tests for {@see \Laminas\Db\Sql\TableIdentifier}
 */
#[CoversClass(TableIdentifier::class)]
class TableIdentifierTest extends TestCase
{
    public function testGetTable()
    {
        $tableIdentifier = new TableIdentifier('foo');

        self::assertSame('foo', $tableIdentifier->getTable());
    }

    public function testGetDefaultSchema()
    {
        $tableIdentifier = new TableIdentifier('foo');

        self::assertNull($tableIdentifier->getSchema());
    }

    public function testGetSchema()
    {
        $tableIdentifier = new TableIdentifier('foo', 'bar');

        self::assertSame('bar', $tableIdentifier->getSchema());
    }

    public function testGetTableFromObjectStringCast()
    {
        $table = $this->getMockBuilder('stdClass')->getMock();

        $table->expects($this->once())->method('__toString')->willReturn('castResult');

        $tableIdentifier = new TableIdentifier($table);

        self::assertSame('castResult', $tableIdentifier->getTable());
        self::assertSame('castResult', $tableIdentifier->getTable());
    }

    public function testGetSchemaFromObjectStringCast()
    {
        $schema = $this->getMockBuilder('stdClass')->getMock();

        $schema->expects($this->once())->method('__toString')->willReturn('castResult');

        $tableIdentifier = new TableIdentifier('foo', $schema);

        self::assertSame('castResult', $tableIdentifier->getSchema());
        self::assertSame('castResult', $tableIdentifier->getSchema());
    }

    /**
     * @param mixed $invalidTable
     */
    #[DataProvider('invalidTableProvider')]
    public function testRejectsInvalidTable($invalidTable)
    {
        $this->expectException(InvalidArgumentException::class);

        new TableIdentifier($invalidTable);
    }

    /**
     * @param mixed $invalidSchema
     */
    #[DataProvider('invalidSchemaProvider')]
    public function testRejectsInvalidSchema($invalidSchema)
    {
        $this->expectException(InvalidArgumentException::class);

        new TableIdentifier('foo', $invalidSchema);
    }

    /**
     * Data provider
     *
     * @return mixed[][]
     */
    public static function invalidTableProvider()
    {
        return array_merge(
            [[null]],
            self::invalidSchemaProvider()
        );
    }

    /**
     * Data provider
     *
     * @return mixed[][]
     */
    public static function invalidSchemaProvider()
    {
        return [
            [''],
            [new stdClass()],
            [[]],
        ];
    }
}
