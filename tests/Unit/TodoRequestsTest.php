<?php

namespace Tests\Unit;

use App\Http\Requests\Todo\StoreTodoRequest;
use App\Http\Requests\Todo\UpdateTodoRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class TodoRequestsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test StoreTodoRequest validation passes with valid data.
     */
    public function test_store_todo_request_passes_with_valid_data(): void
    {
        $request = new StoreTodoRequest();
        
        $validator = Validator::make([
            'title' => 'Test Todo',
            'body' => 'This is a test todo',
            'completed' => false
        ], $request->rules());
        
        $this->assertFalse($validator->fails());
    }

    /**
     * Test StoreTodoRequest validation fails with missing title.
     */
    public function test_store_todo_request_fails_with_missing_title(): void
    {
        $request = new StoreTodoRequest();
        
        $validator = Validator::make([
            'body' => 'This is a test todo',
            'completed' => false
        ], $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('title'));
    }

    /**
     * Test StoreTodoRequest validation fails with title too long.
     */
    public function test_store_todo_request_fails_with_title_too_long(): void
    {
        $request = new StoreTodoRequest();
        
        $validator = Validator::make([
            'title' => str_repeat('a', 101), // 101 characters, max is 100
            'body' => 'This is a test todo',
            'completed' => false
        ], $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('title'));
    }

    /**
     * Test StoreTodoRequest validation fails with body too long.
     */
    public function test_store_todo_request_fails_with_body_too_long(): void
    {
        $request = new StoreTodoRequest();
        
        $validator = Validator::make([
            'title' => 'Test Todo',
            'body' => str_repeat('a', 301), // 301 characters, max is 300
            'completed' => false
        ], $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('body'));
    }

    /**
     * Test UpdateTodoRequest validation passes with valid data.
     */
    public function test_update_todo_request_passes_with_valid_data(): void
    {
        $request = new UpdateTodoRequest();
        
        $validator = Validator::make([
            'title' => 'Updated Todo',
            'body' => 'This is an updated todo',
            'completed' => true
        ], $request->rules());
        
        $this->assertFalse($validator->fails());
    }

    /**
     * Test UpdateTodoRequest validation passes with partial data.
     */
    public function test_update_todo_request_passes_with_partial_data(): void
    {
        $request = new UpdateTodoRequest();
        
        // Only updating the completed status
        $validator = Validator::make([
            'completed' => true
        ], $request->rules());
        
        $this->assertFalse($validator->fails());
    }

    /**
     * Test UpdateTodoRequest validation fails with title too long.
     */
    public function test_update_todo_request_fails_with_title_too_long(): void
    {
        $request = new UpdateTodoRequest();
        
        $validator = Validator::make([
            'title' => str_repeat('a', 101), // 101 characters, max is 100
        ], $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('title'));
    }

    /**
     * Test custom validation messages are set correctly.
     */
    public function test_custom_validation_messages_are_set_correctly(): void
    {
        $request = new StoreTodoRequest();
        $messages = $request->messages();
        
        $this->assertArrayHasKey('title.required', $messages);
        $this->assertArrayHasKey('title.max', $messages);
        $this->assertArrayHasKey('body.required', $messages);
        $this->assertArrayHasKey('body.max', $messages);
    }
}
