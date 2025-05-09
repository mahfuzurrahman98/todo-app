<?php

namespace Tests\Unit;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test todo belongs to a user.
     */
    public function test_todo_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $todo->user);
        $this->assertEquals($user->id, $todo->user->id);
    }

    /**
     * Test todo creation with factory.
     */
    public function test_todo_factory_creates_valid_todo(): void
    {
        // Create a user first
        $user = User::factory()->create();
        
        // Create a todo with the specific user
        $todo = Todo::factory()->create([
            'user_id' => $user->id
        ]);
        
        $this->assertNotNull($todo->id);
        $this->assertNotNull($todo->title);
        $this->assertEquals($user->id, $todo->user_id);
        $this->assertIsBool($todo->completed);
    }

    /**
     * Test todo default values.
     */
    public function test_todo_has_default_values(): void
    {
        $user = User::factory()->create();

        $todo = Todo::create([
            'title' => 'Test Todo',
            'body' => 'Test body',
            'user_id' => $user->id
        ]);

        $this->assertFalse($todo->completed);
    }
}
