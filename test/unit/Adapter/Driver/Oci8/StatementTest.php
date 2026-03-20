<?php

namespace LaminasTest\Db\Adapter\Driver\Oci8;

use Laminas\Db\Adapter\Driver\Oci8\Oci8;
use Laminas\Db\Adapter\Driver\Oci8\Statement;
use Laminas\Db\Adapter\ParameterContainer;
use Laminas\Db\Adapter\Profiler\Profiler;
use PHPUnit\Framework\TestCase;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Statement::class, 'setDriver')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Statement::class, 'setProfiler')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Statement::class, 'getProfiler')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Statement::class, 'initialize')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Statement::class, 'setSql')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Statement::class, 'setParameterContainer')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Statement::class, 'getParameterContainer')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Statement::class, 'getResource')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Statement::class, 'getSql')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Statement::class, 'prepare')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Statement::class, 'isPrepared')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\Oci8\Statement::class, 'execute')]
#[\PHPUnit\Framework\Attributes\Group('integrationOracle')]
class StatementTest extends TestCase
{
    /** @var Statement */
    protected $statement;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->statement = new Statement();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
    }

    public function testSetDriver()
    {
        self::assertEquals($this->statement, $this->statement->setDriver(new Oci8([])));
    }

    public function testSetProfiler()
    {
        self::assertEquals($this->statement, $this->statement->setProfiler(new Profiler()));
    }

    public function testGetProfiler()
    {
        $profiler = new Profiler();
        $this->statement->setProfiler($profiler);
        self::assertEquals($profiler, $this->statement->getProfiler());
    }

    public function testInitialize()
    {
        $oci8 = new Oci8([]);
        self::assertEquals($this->statement, $this->statement->initialize($oci8));
    }

    public function testSetSql()
    {
        self::assertEquals($this->statement, $this->statement->setSql('select * from table'));
        self::assertEquals('select * from table', $this->statement->getSql());
    }

    public function testSetParameterContainer()
    {
        self::assertSame($this->statement, $this->statement->setParameterContainer(new ParameterContainer()));
    }

    /**
     * @todo   Implement testGetParameterContainer().
     */
    public function testGetParameterContainer()
    {
        $container = new ParameterContainer();
        $this->statement->setParameterContainer($container);
        self::assertSame($container, $this->statement->getParameterContainer());
    }

    /**
     * @todo   Implement testGetResource().
     */
    public function testGetResource()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @todo   Implement testGetSql().
     */
    public function testGetSql()
    {
        self::assertEquals($this->statement, $this->statement->setSql('select * from table'));
        self::assertEquals('select * from table', $this->statement->getSql());
    }

    /**
     * @todo   Implement testPrepare().
     */
    public function testPrepare()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testIsPrepared()
    {
        self::assertFalse($this->statement->isPrepared());
    }

    /**
     * @todo   Implement testExecute().
     */
    public function testExecute()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
