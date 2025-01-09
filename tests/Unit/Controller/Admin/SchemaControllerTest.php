<?php

namespace Tests\Unit\Controllers\Admin;

use App\Http\Controllers\Admin\SchemaController;
use App\Services\SchemaManager;
use Illuminate\Http\Request;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SchemaControllerTest extends TestCase
{
    protected SchemaManager $schemaManager;
    protected SchemaController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock SchemaManager to control its behavior in tests
        $this->schemaManager = Mockery::mock(SchemaManager::class);
        $this->controller = new SchemaController($this->schemaManager);
    }

    /**
     * Test that `index` returns a successful response when tables are retrieved.
     */
    #[Test]
    public function it_returns_successful_response_with_tables()
    {
        // Arrange: Set up the expected behavior for getTables
        $this->schemaManager
            ->shouldReceive('getTables')
            ->once()
            ->andReturn(['table1', 'table2']);

        // Act: Call the index method
        $response = $this->controller->index();

        // Assert: Verify the response is as expected
        $this->assertEquals([
            'success' => true,
            'message' => 'Tables retrieved successfully',
            'data' => ['table1', 'table2']
        ], $response);
    }


    /**
     * Test that `index` returns a server error when `getTables` fails.
     */
    #[Test]
    public function it_returns_server_error_when_index_fails()
    {
        // Arrange: Set up the mock to throw an exception when getTables is called
        $this->schemaManager
            ->shouldReceive('getTables')
            ->once()
            ->andThrow(new \Exception('Error retrieving tables'));

        // Act: Call the index method
        $response = $this->controller->index();

        // Assert: Check that the response includes the failure details
        $this->assertEquals([
            'success' => false,
            'message' => 'Failed to retrieve tables',
            'data' => null,
            'debug' => [
                'stack' => [
                    'message' => 'Error retrieving tables',
                    // additional stack trace or debug details can be asserted here as needed
                ],
                'code' => 500,
            ]
        ], $response);
    }

    #[Test]
    public function it_can_create_a_table()
    {
        $validData = [
            'name' => 'new_table',
            'columns' => [['name' => 'column1', 'type' => 'string']],
            'include_id' => true,
        ];

        $this->schemaManager->shouldReceive('createTable')
            ->once()
            ->with($validData)
            ->andReturn(true);

        $response = $this->controller->store(new Request($validData));

        $this->assertEquals(201, $response->status());
        $this->assertEquals(['message' => 'Table created successfully'], $response->getData(true));
    }

    #[Test]
    public function it_returns_validation_error_when_store_fails()
    {
        $invalidData = [
            'name' => '',
            'columns' => []
        ];

        $response = $this->controller->store(new Request($invalidData));

        $this->assertEquals(422, $response->status());
        $this->assertArrayHasKey('errors', $response->getData(true));
    }

    #[Test]
    public function it_returns_server_error_when_store_fails()
    {
        $validData = [
            'name' => 'new_table',
            'columns' => [['name' => 'column1', 'type' => 'string']]
        ];

        $this->schemaManager->shouldReceive('createTable')
            ->once()
            ->with($validData)
            ->andThrow(new \Exception('Error creating table'));

        $response = $this->controller->store(new Request($validData));

        $this->assertEquals(500, $response->status());
        $this->assertEquals(['message' => 'Failed to create table'], $response->getData(true));
    }

    #[Test]
    public function it_can_update_a_table()
    {
        $updateData = [
            'add_columns' => [['name' => 'column2', 'type' => 'integer']],
        ];

        $this->schemaManager->shouldReceive('updateTable')
            ->once()
            ->with('test_table', $updateData)
            ->andReturn(true);

        $response = $this->controller->update(new Request($updateData), 'test_table');

        $this->assertEquals(200, $response->status());
        $this->assertEquals(['message' => 'Table updated successfully'], $response->getData(true));
    }

    #[Test]
    public function it_returns_server_error_when_update_fails()
    {
        $updateData = [
            'add_columns' => [['name' => 'column2', 'type' => 'integer']],
        ];

        $this->schemaManager->shouldReceive('updateTable')
            ->once()
            ->with('test_table', $updateData)
            ->andThrow(new \Exception('Error updating table'));

        $response = $this->controller->update(new Request($updateData), 'test_table');

        $this->assertEquals(500, $response->status());
        $this->assertEquals(['message' => 'Failed to update table'], $response->getData(true));
    }

    #[Test]
    public function it_can_delete_a_table()
    {
        $this->schemaManager->shouldReceive('deleteTable')
            ->once()
            ->with('test_table')
            ->andReturn(true);

        $response = $this->controller->destroy('test_table');

        $this->assertEquals(200, $response->status());
        $this->assertEquals(['message' => 'Table deleted successfully'], $response->getData(true));
    }

    #[Test]
    public function it_returns_server_error_when_destroy_fails()
    {
        $this->schemaManager->shouldReceive('deleteTable')
            ->once()
            ->with('test_table')
            ->andThrow(new \Exception('Error deleting table'));

        $response = $this->controller->destroy('test_table');

        $this->assertEquals(500, $response->status());
        $this->assertEquals(['message' => 'Failed to delete table'], $response->getData(true));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
