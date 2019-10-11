<?php

/**
 * Spiral, Core Components
 *
 * @author Wolfy-J
 */
declare(strict_types=1);

namespace Spiral\Database\Tests;

use Spiral\Database\Database;
use Spiral\Database\Schema\AbstractTable;

abstract class CreateTableTest extends BaseTest
{
    /**
     * @var Database
     */
    protected $database;

    public function setUp(): void
    {
        $this->database = $this->db();
    }

    public function tearDown(): void
    {
        $this->dropDatabase($this->db());
    }

    public function schema(string $table): AbstractTable
    {
        return $this->database->table($table)->getSchema();
    }

    public function testEmptyTable(): void
    {
        $schema = $this->schema('table');
        $this->assertFalse($schema->exists());

        $this->assertSame([], $schema->getPrimaryKeys());
        $this->assertSame([], $schema->getColumns());
        $this->assertSame([], $schema->getIndexes());
        $this->assertSame([], $schema->getForeignKeys());
    }

    public function testSimpleCreation(): void
    {
        $schema = $this->schema('table');
        $this->assertFalse($schema->exists());

        $schema->primary('id');
        $schema->save();

        $schema = $this->schema('table');
        $this->assertTrue($schema->exists());
        $this->assertSameAsInDB($schema);

        $this->assertInternalType('array', $schema->__debugInfo());
    }

    public function testMultipleColumns(): void
    {
        $schema = $this->schema('table');
        $this->assertFalse($schema->exists());

        $schema->primary('id');
        $schema->string('name');
        $schema->enum('status', ['active', 'disabled']);
        $schema->float('balance')->defaultValue(0);

        $schema->save();

        $schema = $this->schema('table');
        $this->assertTrue($schema->exists());
        $this->assertSameAsInDB($schema);

        $this->assertSame(['active', 'disabled'], $schema->column('status')->getEnumValues());
    }

    public function testCreateAndDrop(): void
    {
        $schema = $this->schema('table');
        $this->assertFalse($schema->exists());

        $schema->primary('id');
        $schema->save();

        $this->assertSame('table', $schema->column('id')->getTable());

        $this->assertTrue($schema->exists());

        $schema->declareDropped();
        $schema->save();

        $schema = $this->schema('table');
        $this->assertFalse($schema->exists());
    }

    public function testCreateNoPrimary(): void
    {
        $schema = $this->schema('table');
        $this->assertFalse($schema->exists());

        $schema->string('name');
        $this->assertSame([], $schema->getPrimaryKeys());
        $schema->save();

        $schema = $this->schema('table');
        $this->assertTrue($schema->exists());

        $this->assertTrue($schema->hasColumn('name'));
    }

    public function testCreateWithPrimary(): void
    {
        $schema = $this->schema('table');
        $this->assertFalse($schema->exists());

        $schema->integer('id')->nullable(false);
        $schema->string('name');

        $schema->setPrimaryKeys(['id']);
        $this->assertSame(['id'], $schema->getPrimaryKeys());
        $schema->save();

        $schema = $this->schema('table');
        $this->assertTrue($schema->exists());

        $this->assertSameAsInDB($schema);
        $this->assertSame(['id'], $this->fetchSchema($schema)->getPrimaryKeys());
    }

    /**
     * @expectedException \Spiral\Database\Exception\SchemaException
     */
    public function testDeleteNonExisted(): void
    {
        $schema = $this->schema('table');
        $this->assertFalse($schema->exists());
        $schema->declareDropped();
    }
}
