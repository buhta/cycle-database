<?php

declare(strict_types=1);

namespace Cycle\Database\Tests\Functional\Driver\Postgres\Connection;

// phpcs:ignore
use Cycle\Database\Tests\Functional\Driver\Common\Connection\CustomOptionsTest as CommonClass;

/**
 * @group driver
 * @group driver-postgres
 */
class CustomOptionsTest extends CommonClass
{
    public const DRIVER = 'postgres';
}
