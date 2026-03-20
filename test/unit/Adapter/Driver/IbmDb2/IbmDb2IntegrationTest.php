<?php

namespace LaminasTest\Db\Adapter\Driver\IbmDb2;

use Laminas\Db\Adapter\Driver\IbmDb2\IbmDb2;
use Laminas\Db\Adapter\Driver\IbmDb2\Statement;
use Laminas\Db\Adapter\Exception\InvalidArgumentException;
use stdClass;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Driver\IbmDb2\IbmDb2::class, 'checkEnvironment')]
#[\PHPUnit\Framework\Attributes\Group('integration')]
#[\PHPUnit\Framework\Attributes\Group('integration-ibm_db2')]
class IbmDb2IntegrationTest extends AbstractIntegrationTest
{
    #[\PHPUnit\Framework\Attributes\Group('integration-ibm_db2')]
    public function testCheckEnvironment()
    {
        $ibmdb2 = new IbmDb2([]);
        self::assertNull($ibmdb2->checkEnvironment());
    }

    public function testCreateStatement()
    {
        $driver = new IbmDb2([]);

        $resource = db2_connect(
            $this->variables['database'],
            $this->variables['username'],
            $this->variables['password']
        );

        $stmtResource = db2_prepare($resource, 'SELECT 1 FROM SYSIBM.SYSDUMMY1');

        $driver->getConnection()->setResource($resource);

        $stmt = $driver->createStatement('SELECT 1 FROM SYSIBM.SYSDUMMY1');
        self::assertInstanceOf(Statement::class, $stmt);
        $stmt = $driver->createStatement($stmtResource);
        self::assertInstanceOf(Statement::class, $stmt);
        $stmt = $driver->createStatement();
        self::assertInstanceOf(Statement::class, $stmt);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('only accepts an SQL string or an ibm_db2 resource');
        $driver->createStatement(new stdClass());
    }
}
