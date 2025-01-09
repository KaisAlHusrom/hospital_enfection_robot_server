<?php

namespace Tests\Unit;

use App\Services\SchemaManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test as AttributesTest;
use Tests\TestCase;

class SchemaManagerTest extends TestCase
{
    use RefreshDatabase;

    protected SchemaManager $schemaManager;

    /**
     * Sets up a new instance of SchemaManager before each test.
     * This method is called automatically by PHPUnit.
     */
    #[AttributesTest]
    protected function setUp(): void
    {
        parent::setUp();
        $this->schemaManager = new SchemaManager();
    }

    /**
     * Tests the creation of a new table with specific columns.
     * The table, 'test_table', is created with an ID, a name (string), an age (integer with a default value of 0),
     * timestamps, and soft deletes. After creation, it checks that the table and all specified columns exist in the database.
     */
    #[AttributesTest]
    public function it_can_create_a_table()
    {
        $tableData = [
            'name' => 'test_table',
            'columns' => [
                ['name' => 'name', 'type' => 'string', 'nullable' => false],
                ['name' => 'age', 'type' => 'integer', 'nullable' => true, 'default' => 0],
            ],
            'include_id' => true,
            'include_timestamps' => true,
            'soft_deletes' => true,
        ];

        $this->schemaManager->createTable($tableData);

        $this->assertTrue(Schema::hasTable('test_table'));
        $this->assertTrue(Schema::hasColumn('test_table', 'name'));
        $this->assertTrue(Schema::hasColumn('test_table', 'age'));
        $this->assertTrue(Schema::hasColumn('test_table', 'created_at'));
        $this->assertTrue(Schema::hasColumn('test_table', 'deleted_at'));
    }

    /**
     * Tests that the table information is cached after retrieval.
     * It calls the getTables method to retrieve and cache table data.
     * The test then checks that a cache entry exists for the tables list.
     */
    #[AttributesTest]
    public function it_caches_table_information()
    {
        $this->schemaManager->getTables();
        $this->assertTrue(Cache::has('schema_tables'));
    }

    /**
     * Tests adding and removing columns in an existing table.
     * First, it creates 'test_update_columns_table' with a 'name' column.
     * Then, it adds a new 'age' column and verifies its existence.
     * Afterward, it removes the 'age' column and checks that it no longer exists.
     */
    #[AttributesTest]
    public function it_can_add_and_remove_columns()
    {
        // Create table
        $this->schemaManager->createTable([
            'name' => 'test_update_columns_table',
            'columns' => [['name' => 'name', 'type' => 'string']],
        ]);

        // Update table: add column
        $this->schemaManager->updateTable('test_update_columns_table', [
            'add_columns' => [['name' => 'age', 'type' => 'integer']],
        ]);
        $this->assertTrue(Schema::hasColumn('test_update_columns_table', 'age'));

        // Update table: drop column
        $this->schemaManager->updateTable('test_update_columns_table', [
            'drop_columns' => ['age'],
        ]);
        $this->assertFalse(Schema::hasColumn('test_update_columns_table', 'age'));
    }

    /**
     * Tests that cache entries related to a table are cleared upon table deletion.
     * It first creates a 'test_clear_cache_table' table, then deletes it.
     * The test then verifies that the table no longer exists and that the table cache has been cleared.
     */
    #[AttributesTest]
    public function it_clears_cache_when_table_is_deleted()
    {
        $this->schemaManager->createTable([
            'name' => 'test_clear_cache_table',
            'columns' => [['name' => 'name', 'type' => 'string']],
        ]);

        $this->schemaManager->deleteTable('test_clear_cache_table');

        $this->assertFalse(Schema::hasTable('test_clear_cache_table'));
        $this->assertFalse(Cache::has('schema_tables'));
    }
}
