<?php

namespace Tests\Feature;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoApiTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $token;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a user and generate token
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('auth_token')->plainTextToken;
    }

    /**
     * Test user can get all todos.
     */
    public function test_user_can_get_all_todos(): void
    {
        // Create some todos for the user
        Todo::factory()->count(3)->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/todos');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'todos' => [
                        '*' => [
                            'id',
                            'title',
                            'body',
                            'completed',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]
            ])
            ->assertJsonCount(3, 'data.todos');
    }

    /**
     * Test user can create a todo.
     */
    public function test_user_can_create_a_todo(): void
    {
        $todoData = [
            'title' => 'Test Todo',
            'body' => 'This is a test todo',
            'completed' => false
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/todos', $todoData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'todo' => [
                        'id',
                        'title',
                        'body',
                        'completed',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);

        $this->assertDatabaseHas('todos', [
            'title' => 'Test Todo',
            'body' => 'This is a test todo',
            'user_id' => $this->user->id
        ]);
    }

    /**
     * Test validation when creating a todo.
     */
    public function test_todo_creation_requires_title(): void
    {
        $todoData = [
            'body' => 'This is a test todo',
            'completed' => false
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/todos', $todoData);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The todo title is required'
            ]);
    }

    /**
     * Test validation when creating a todo with title too long.
     */
    public function test_todo_creation_validates_title_length(): void
    {
        $todoData = [
            'title' => str_repeat('a', 101), // 101 characters, max is 100
            'body' => 'This is a test todo',
            'completed' => false
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/todos', $todoData);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The todo title must not exceed 100 characters'
            ]);
    }

    /**
     * Test validation when creating a todo with body too long.
     */
    public function test_todo_creation_validates_body_length(): void
    {
        $todoData = [
            'title' => 'Test Todo',
            'body' => str_repeat('a', 301), // 301 characters, max is 300
            'completed' => false
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/todos', $todoData);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The todo body must not exceed 300 characters'
            ]);
    }

    /**
     * Test user can view a specific todo.
     */
    public function test_user_can_view_a_specific_todo(): void
    {
        $todo = Todo::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/todos/' . $todo->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'todo' => [
                        'id',
                        'title',
                        'body',
                        'completed',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ])
            ->assertJson([
                'data' => [
                    'todo' => [
                        'id' => $todo->id,
                        'title' => $todo->title
                    ]
                ]
            ]);
    }

    /**
     * Test user cannot view another user's todo.
     */
    public function test_user_cannot_view_another_users_todo(): void
    {
        $anotherUser = User::factory()->create();
        $todo = Todo::factory()->create([
            'user_id' => $anotherUser->id
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/todos/' . $todo->id);

        $response->assertStatus(403);
    }

    /**
     * Test user can update their todo.
     */
    public function test_user_can_update_their_todo(): void
    {
        $todo = Todo::factory()->create([
            'user_id' => $this->user->id,
            'completed' => false
        ]);

        $updateData = [
            'title' => 'Updated Todo Title',
            'body' => 'Updated todo body',
            'completed' => true
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('/api/todos/' . $todo->id, $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'todo' => [
                        'id',
                        'title',
                        'body',
                        'completed',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ])
            ->assertJson([
                'data' => [
                    'todo' => [
                        'title' => 'Updated Todo Title',
                        'body' => 'Updated todo body',
                        'completed' => true
                    ]
                ]
            ]);

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => 'Updated Todo Title',
            'body' => 'Updated todo body',
            'completed' => true
        ]);
    }

    /**
     * Test user cannot update another user's todo.
     */
    public function test_user_cannot_update_another_users_todo(): void
    {
        $anotherUser = User::factory()->create();
        $todo = Todo::factory()->create([
            'user_id' => $anotherUser->id
        ]);

        $updateData = [
            'title' => 'Updated Todo Title',
            'body' => 'Updated todo body',
            'completed' => true
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('/api/todos/' . $todo->id, $updateData);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('todos', [
            'id' => $todo->id,
            'title' => 'Updated Todo Title'
        ]);
    }

    /**
     * Test user can delete their todo.
     */
    public function test_user_can_delete_their_todo(): void
    {
        $todo = Todo::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/todos/' . $todo->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }

    /**
     * Test user cannot delete another user's todo.
     */
    public function test_user_cannot_delete_another_users_todo(): void
    {
        $anotherUser = User::factory()->create();
        $todo = Todo::factory()->create([
            'user_id' => $anotherUser->id
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/todos/' . $todo->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('todos', ['id' => $todo->id]);
    }

    /**
     * Test unauthenticated user cannot access todos.
     */
    public function test_unauthenticated_user_cannot_access_todos(): void
    {
        $response = $this->getJson('/api/todos');
        $response->assertStatus(401);
    }
}
