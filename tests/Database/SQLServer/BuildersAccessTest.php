<?php

/**
 * Spiral, Core Components
 *
 * @author Wolfy-J
 */
declare(strict_types=1);

namespace Spiral\Database\Tests\SQLServer;

use Spiral\Database\Driver\SQLServer\Schema\SQLServerTable;

class BuildersAccessTest extends \Spiral\Database\Tests\BuildersAccessTest
{
    public const DRIVER = 'sqlserver';

    public function testTableSchemaAccess(): void
    {
        parent::testTableSchemaAccess();
        $this->assertInstanceOf(
            SQLServerTable::class,
            $this->db()->table('sample')->getSchema()
        );
    }
}
