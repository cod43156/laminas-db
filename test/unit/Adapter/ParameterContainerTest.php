<?php

namespace LaminasTest\Db\Adapter;

use Laminas\Db\Adapter\ParameterContainer;
use PHPUnit\Framework\TestCase;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\ParameterContainer::class, 'offsetExists')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\ParameterContainer::class, 'offsetGet')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\ParameterContainer::class, 'offsetSet')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\ParameterContainer::class, 'offsetUnset')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\ParameterContainer::class, 'setFromArray')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\ParameterContainer::class, 'offsetSetMaxLength')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\ParameterContainer::class, 'offsetGetMaxLength')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\ParameterContainer::class, 'offsetHasMaxLength')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\ParameterContainer::class, 'offsetUnsetMaxLength')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\ParameterContainer::class, 'getMaxLengthIterator')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\ParameterContainer::class, 'offsetSetErrata')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\ParameterContainer::class, 'offsetGetErrata')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\ParameterContainer::class, 'offsetHasErrata')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\ParameterContainer::class, 'offsetUnsetErrata')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\ParameterContainer::class, 'getErrataIterator')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\ParameterContainer::class, 'getNamedArray')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\ParameterContainer::class, 'count')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\ParameterContainer::class, 'current')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\ParameterContainer::class, 'next')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\ParameterContainer::class, 'key')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\ParameterContainer::class, 'valid')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\ParameterContainer::class, 'rewind')]
class ParameterContainerTest extends TestCase
{
    /** @var ParameterContainer */
    protected $parameterContainer;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->parameterContainer = new ParameterContainer(['foo' => 'bar']);
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test offsetExists() returns proper values via method call and isset()')]
    public function testOffsetExists()
    {
        self::assertTrue($this->parameterContainer->offsetExists('foo'));
        self::assertTrue(isset($this->parameterContainer['foo']));
        self::assertFalse($this->parameterContainer->offsetExists('bar'));
        self::assertFalse(isset($this->parameterContainer['bar']));
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test offsetGet() returns proper values via method call and array access')]
    public function testOffsetGet()
    {
        self::assertEquals('bar', $this->parameterContainer->offsetGet('foo'));
        self::assertEquals('bar', $this->parameterContainer['foo']);

        self::assertNull($this->parameterContainer->offsetGet('bar'));
        // @todo determine what should come back here
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test offsetSet() works via method call and array access')]
    public function testOffsetSet()
    {
        $this->parameterContainer->offsetSet('boo', 'baz');
        self::assertEquals('baz', $this->parameterContainer->offsetGet('boo'));

        $this->parameterContainer->offsetSet('1', 'book', ParameterContainer::TYPE_STRING, 4);
        self::assertEquals(
            ['foo' => 'bar', 'boo' => 'baz', '1' => 'book'],
            $this->parameterContainer->getNamedArray()
        );

        self::assertEquals('string', $this->parameterContainer->offsetGetErrata('1'));
        self::assertEquals(4, $this->parameterContainer->offsetGetMaxLength('1'));

        // test that setting an index applies to correct named parameter
        $this->parameterContainer[0] = 'Zero';
        $this->parameterContainer[1] = 'One';
        self::assertEquals(
            ['foo' => 'Zero', 'boo' => 'One', '1' => 'book'],
            $this->parameterContainer->getNamedArray()
        );
        self::assertEquals(
            [0 => 'Zero', 1 => 'One', 2 => 'book'],
            $this->parameterContainer->getPositionalArray()
        );

        // test no-index applies
        $this->parameterContainer['buffer'] = 'A buffer Element';
        $this->parameterContainer[]         = 'Second To Last';
        $this->parameterContainer[]         = 'Last';
        self::assertEquals(
            [
                'foo'    => 'Zero',
                'boo'    => 'One',
                '1'      => 'book',
                'buffer' => 'A buffer Element',
                '4'      => 'Second To Last',
                '5'      => 'Last',
            ],
            $this->parameterContainer->getNamedArray()
        );
        self::assertEquals(
            [0 => 'Zero', 1 => 'One', 2 => 'book', 3 => 'A buffer Element', 4 => 'Second To Last', 5 => 'Last'],
            $this->parameterContainer->getPositionalArray()
        );
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test offsetUnset() works via method call and array access')]
    public function testOffsetUnset()
    {
        $this->parameterContainer->offsetSet('boo', 'baz');
        self::assertTrue($this->parameterContainer->offsetExists('boo'));

        $this->parameterContainer->offsetUnset('boo');
        self::assertFalse($this->parameterContainer->offsetExists('boo'));
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test setFromArray() will populate the container')]
    public function testSetFromArray()
    {
        $this->parameterContainer->setFromArray(['bar' => 'baz']);
        self::assertEquals('baz', $this->parameterContainer['bar']);
    }

    /**
     * Handle statement parameters - https://github.com/laminas/laminas-db/issues/47
     *
     * @see Insert::procesInsert as example
     */
    public function testSetFromArrayNamed()
    {
        $this->parameterContainer->offsetSet('c_0', ':myparam');
        $this->parameterContainer->setFromArray([':myparam' => 'baz']);
        self::assertEquals('baz', $this->parameterContainer['c_0']);
        self::assertEquals('baz', $this->parameterContainer[':myparam']);
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test offsetSetMaxLength() will persist errata data. Return persisted errata data, if it exists')]
    public function testOffsetSetAndGetMaxLength()
    {
        $this->parameterContainer->offsetSetMaxLength('foo', 100);
        self::assertEquals(100, $this->parameterContainer->offsetGetMaxLength('foo'));
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test offsetHasMaxLength() will check if errata exists for a particular key')]
    public function testOffsetHasMaxLength()
    {
        $this->parameterContainer->offsetSetMaxLength('foo', 100);
        self::assertTrue($this->parameterContainer->offsetHasMaxLength('foo'));
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test offsetUnsetMaxLength() will unset data for a particular key')]
    public function testOffsetUnsetMaxLength()
    {
        $this->parameterContainer->offsetSetMaxLength('foo', 100);
        $this->parameterContainer->offsetUnsetMaxLength('foo');
        self::assertNull($this->parameterContainer->offsetGetMaxLength('foo'));
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test getMaxLengthIterator() will return an iterator for the errata data')]
    public function testGetMaxLengthIterator()
    {
        $this->parameterContainer->offsetSetMaxLength('foo', 100);
        $data = $this->parameterContainer->getMaxLengthIterator();
        self::assertInstanceOf('ArrayIterator', $data);
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test offsetSetErrata() will persist errata data')]
    public function testOffsetSetErrata()
    {
        $this->parameterContainer->offsetSetErrata('foo', ParameterContainer::TYPE_INTEGER);
        self::assertEquals(ParameterContainer::TYPE_INTEGER, $this->parameterContainer->offsetGetErrata('foo'));
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test offsetGetErrata() return persisted errata data, if it exists')]
    public function testOffsetGetErrata()
    {
        $this->parameterContainer->offsetSetErrata('foo', ParameterContainer::TYPE_INTEGER);
        self::assertEquals(ParameterContainer::TYPE_INTEGER, $this->parameterContainer->offsetGetErrata('foo'));
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test offsetHasErrata() will check if errata exists for a particular key')]
    public function testOffsetHasErrata()
    {
        $this->parameterContainer->offsetSetErrata('foo', ParameterContainer::TYPE_INTEGER);
        self::assertTrue($this->parameterContainer->offsetHasErrata('foo'));
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test offsetUnsetErrata() will unset data for a particular key')]
    public function testOffsetUnsetErrata()
    {
        $this->parameterContainer->offsetSetErrata('foo', ParameterContainer::TYPE_INTEGER);
        $this->parameterContainer->offsetUnsetErrata('foo');
        self::assertNull($this->parameterContainer->offsetGetErrata('foo'));
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test getErrataIterator() will return an iterator for the errata data')]
    public function testGetErrataIterator()
    {
        $this->parameterContainer->offsetSetErrata('foo', ParameterContainer::TYPE_INTEGER);
        $data = $this->parameterContainer->getErrataIterator();
        self::assertInstanceOf('ArrayIterator', $data);
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test getNamedArray()')]
    public function testGetNamedArray()
    {
        $data = $this->parameterContainer->getNamedArray();
        self::assertEquals(['foo' => 'bar'], $data);
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test count() returns the proper count')]
    public function testCount()
    {
        self::assertEquals(1, $this->parameterContainer->count());
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test current() returns the current element when used as an iterator')]
    public function testCurrent()
    {
        $value = $this->parameterContainer->current();
        self::assertEquals('bar', $value);
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test next() increases the pointer when used as an iterator')]
    public function testNext()
    {
        $this->parameterContainer['bar'] = 'baz';
        $this->parameterContainer->next();
        self::assertEquals('baz', $this->parameterContainer->current());
    }

    #[\PHPUnit\Framework\Attributes\TestDox("unit test: Test key() returns the name of the current item's name")]
    public function testKey()
    {
        self::assertEquals('foo', $this->parameterContainer->key());
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test valid() returns whether the iterators current position is valid')]
    public function testValid()
    {
        self::assertTrue($this->parameterContainer->valid());
        $this->parameterContainer->next();
        self::assertFalse($this->parameterContainer->valid());
    }

    #[\PHPUnit\Framework\Attributes\TestDox('unit test: Test rewind() resets the iterators pointer')]
    public function testRewind()
    {
        $this->parameterContainer->offsetSet('bar', 'baz');
        $this->parameterContainer->next();
        self::assertEquals('bar', $this->parameterContainer->key());
        $this->parameterContainer->rewind();
        self::assertEquals('foo', $this->parameterContainer->key());
    }
}
