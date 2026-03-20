<?php

namespace LaminasTest\Db\ResultSet;

use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Hydrator\ArraySerializable;
use Laminas\Hydrator\ArraySerializableHydrator;
use Laminas\Hydrator\ClassMethods;
use Laminas\Hydrator\ClassMethodsHydrator;
use PHPUnit\Framework\TestCase;
use stdClass;

use function class_exists;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\ResultSet\HydratingResultSet::class, 'setObjectPrototype')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\ResultSet\HydratingResultSet::class, 'getObjectPrototype')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\ResultSet\HydratingResultSet::class, 'setHydrator')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\ResultSet\HydratingResultSet::class, 'getHydrator')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\ResultSet\HydratingResultSet::class, 'current')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\ResultSet\HydratingResultSet::class, 'toArray')]
class HydratingResultSetTest extends TestCase
{
    /** @var string */
    private $arraySerializableHydratorClass;

    /** @var string */
    private $classMethodsHydratorClass;

    protected function setUp(): void
    {
        $this->arraySerializableHydratorClass = class_exists(ArraySerializableHydrator::class)
            ? ArraySerializableHydrator::class
            : ArraySerializable::class;

        $this->classMethodsHydratorClass = class_exists(ClassMethodsHydrator::class)
            ? ClassMethodsHydrator::class
            : ClassMethods::class;
    }

    public function testSetObjectPrototype()
    {
        $prototype   = new stdClass();
        $hydratingRs = new HydratingResultSet();
        self::assertSame($hydratingRs, $hydratingRs->setObjectPrototype($prototype));
    }

    public function testGetObjectPrototype()
    {
        $hydratingRs = new HydratingResultSet();
        self::assertInstanceOf('ArrayObject', $hydratingRs->getObjectPrototype());
    }

    public function testSetHydrator()
    {
        $hydratingRs   = new HydratingResultSet();
        $hydratorClass = $this->classMethodsHydratorClass;
        self::assertSame($hydratingRs, $hydratingRs->setHydrator(new $hydratorClass()));
    }

    public function testGetHydrator()
    {
        $hydratingRs = new HydratingResultSet();
        self::assertInstanceOf($this->arraySerializableHydratorClass, $hydratingRs->getHydrator());
    }

    public function testCurrentHasData()
    {
        $hydratingRs = new HydratingResultSet();
        $hydratingRs->initialize([
            ['id' => 1, 'name' => 'one'],
        ]);
        $obj = $hydratingRs->current();
        self::assertInstanceOf('ArrayObject', $obj);
    }

    public function testCurrentDoesnotHasData()
    {
        $hydratingRs = new HydratingResultSet();
        $hydratingRs->initialize([]);
        $result = $hydratingRs->current();
        self::assertNull($result);
    }

    /**
     * @todo   Implement testToArray().
     */
    public function testToArray()
    {
        $hydratingRs = new HydratingResultSet();
        $hydratingRs->initialize([
            ['id' => 1, 'name' => 'one'],
        ]);
        $obj = $hydratingRs->toArray();
        self::assertIsArray($obj);
    }
}
