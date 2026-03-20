<?php

namespace LaminasTest\Db\Sql\Ddl\Constraint;

use Laminas\Db\Sql\Ddl\Constraint\ForeignKey;
use PHPUnit\Framework\TestCase;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Constraint\ForeignKey::class, 'setName')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Constraint\ForeignKey::class, 'getName')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Constraint\ForeignKey::class, 'setReferenceTable')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Constraint\ForeignKey::class, 'getReferenceTable')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Constraint\ForeignKey::class, 'setReferenceColumn')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Constraint\ForeignKey::class, 'getReferenceColumn')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Constraint\ForeignKey::class, 'setOnDeleteRule')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Constraint\ForeignKey::class, 'getOnDeleteRule')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Constraint\ForeignKey::class, 'setOnUpdateRule')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Constraint\ForeignKey::class, 'getOnUpdateRule')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Constraint\ForeignKey::class, 'getExpressionData')]
class ForeignKeyTest extends TestCase
{
    public function testSetName(): ForeignKey
    {
        $fk = new ForeignKey('foo', 'bar', 'baz', 'bam');
        self::assertSame($fk, $fk->setName('xxxx'));
        return $fk;
    }

    #[\PHPUnit\Framework\Attributes\Depends('testSetName')]
    public function testGetName(ForeignKey $fk)
    {
        self::assertEquals('xxxx', $fk->getName());
    }

    public function testSetReferenceTable(): ForeignKey
    {
        $fk = new ForeignKey('foo', 'bar', 'baz', 'bam');
        self::assertSame($fk, $fk->setReferenceTable('xxxx'));
        return $fk;
    }

    #[\PHPUnit\Framework\Attributes\Depends('testSetReferenceTable')]
    public function testGetReferenceTable(ForeignKey $fk)
    {
        self::assertEquals('xxxx', $fk->getReferenceTable());
    }

    public function testSetReferenceColumn(): ForeignKey
    {
        $fk = new ForeignKey('foo', 'bar', 'baz', 'bam');
        self::assertSame($fk, $fk->setReferenceColumn('xxxx'));
        return $fk;
    }

    #[\PHPUnit\Framework\Attributes\Depends('testSetReferenceColumn')]
    public function testGetReferenceColumn(ForeignKey $fk)
    {
        self::assertEquals(['xxxx'], $fk->getReferenceColumn());
    }

    public function testSetOnDeleteRule(): ForeignKey
    {
        $fk = new ForeignKey('foo', 'bar', 'baz', 'bam');
        self::assertSame($fk, $fk->setOnDeleteRule('CASCADE'));
        return $fk;
    }

    #[\PHPUnit\Framework\Attributes\Depends('testSetOnDeleteRule')]
    public function testGetOnDeleteRule(ForeignKey $fk)
    {
        self::assertEquals('CASCADE', $fk->getOnDeleteRule());
    }

    public function testSetOnUpdateRule(): ForeignKey
    {
        $fk = new ForeignKey('foo', 'bar', 'baz', 'bam');
        self::assertSame($fk, $fk->setOnUpdateRule('CASCADE'));
        return $fk;
    }

    #[\PHPUnit\Framework\Attributes\Depends('testSetOnUpdateRule')]
    public function testGetOnUpdateRule(ForeignKey $fk)
    {
        self::assertEquals('CASCADE', $fk->getOnUpdateRule());
    }

    public function testGetExpressionData()
    {
        $fk = new ForeignKey('foo', 'bar', 'baz', 'bam', 'CASCADE', 'SET NULL');
        self::assertEquals(
            [
                [
                    'CONSTRAINT %s FOREIGN KEY (%s) REFERENCES %s (%s) ON DELETE %s ON UPDATE %s',
                    ['foo', 'bar', 'baz', 'bam', 'CASCADE', 'SET NULL'],
                    [
                        $fk::TYPE_IDENTIFIER,
                        $fk::TYPE_IDENTIFIER,
                        $fk::TYPE_IDENTIFIER,
                        $fk::TYPE_IDENTIFIER,
                        $fk::TYPE_LITERAL,
                        $fk::TYPE_LITERAL,
                    ],
                ],
            ],
            $fk->getExpressionData()
        );
    }
}
