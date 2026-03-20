<?php

namespace LaminasTest\Db\Sql\Ddl\Constraint;

use Laminas\Db\Sql\Ddl\Constraint\AbstractConstraint;
use PHPUnit\Framework\TestCase;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Constraint\AbstractConstraint::class, 'setColumns')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Constraint\AbstractConstraint::class, 'addColumn')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Constraint\AbstractConstraint::class, 'getColumns')]
class AbstractConstraintTest extends TestCase
{
    /** @var AbstractConstraint */
    protected $ac;

    protected function setUp(): void
    {
        $this->ac = $this->getMockForAbstractClass(AbstractConstraint::class);
    }

    public function testSetColumns()
    {
        self::assertSame($this->ac, $this->ac->setColumns(['foo', 'bar']));
        self::assertEquals(['foo', 'bar'], $this->ac->getColumns());
    }

    public function testAddColumn()
    {
        self::assertSame($this->ac, $this->ac->addColumn('foo'));
        self::assertEquals(['foo'], $this->ac->getColumns());
    }

    public function testGetColumns()
    {
        $this->ac->setColumns(['foo', 'bar']);
        self::assertEquals(['foo', 'bar'], $this->ac->getColumns());
    }
}
