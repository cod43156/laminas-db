<?php

namespace LaminasTest\Db\Sql\Ddl\Constraint;

use Laminas\Db\Sql\Ddl\Constraint\PrimaryKey;
use PHPUnit\Framework\TestCase;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Sql\Ddl\Constraint\PrimaryKey::class, 'getExpressionData')]
class PrimaryKeyTest extends TestCase
{
    public function testGetExpressionData()
    {
        $pk = new PrimaryKey('foo');
        self::assertEquals(
            [
                [
                    'PRIMARY KEY (%s)',
                    ['foo'],
                    [$pk::TYPE_IDENTIFIER],
                ],
            ],
            $pk->getExpressionData()
        );
    }
}
