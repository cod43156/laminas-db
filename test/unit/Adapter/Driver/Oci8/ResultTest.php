<?php

namespace LaminasTest\Db\Adapter\Driver\Oci8;

use Laminas\Db\Adapter\Driver\Oci8\Result;
use PHPUnit\Framework\TestCase;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Result::class, 'getResource')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Result::class, 'buffer')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Result::class, 'isBuffered')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Result::class, 'getGeneratedValue')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Result::class, 'key')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Result::class, 'next')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Result::class, 'rewind')]
#[\PHPUnit\Framework\Attributes\Group('result-oci8')]
class ResultTest extends TestCase
{
    public function testGetResource()
    {
        $result = new Result();
        self::assertNull($result->getResource());
    }

    public function testBuffer()
    {
        $result = new Result();
        self::assertNull($result->buffer());
    }

    public function testIsBuffered()
    {
        $result = new Result();
        self::assertFalse($result->isBuffered());
    }

    public function testGetGeneratedValue()
    {
        $result = new Result();
        self::assertNull($result->getGeneratedValue());
    }

    public function testKey()
    {
        $result = new Result();
        self::assertEquals(0, $result->key());
    }

    public function testNext()
    {
        $mockResult = $this->getMockBuilder(Result::class)
            ->getMock();
        $mockResult->expects($this->any())
            ->method('loadData')
            ->willReturn(null);
        self::assertNull($mockResult->next());
    }

    public function testRewind()
    {
        $result = new Result();
        self::assertNull($result->rewind());
    }
}
