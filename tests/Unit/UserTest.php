<?php

namespace Tests\Unit;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user has many todos.
     */
    public function test_user_has_many_todos(): void
    {
        $user = User::factory()->create();
        Todo::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->todos);
        $this->assertCount(3, $user->todos);
        $this->assertInstanceOf(Todo::class, $user->todos->first());
    }

    /**
     * Test user has the correct fillable attributes.
     */
    public function test_user_has_correct_fillable_attributes(): void
    {
        $user = new User();
        
        $this->assertEquals([
            'name',
            'email',
            'password',
        ], $user->getFillable());
    }

    /**
     * Test user has the correct hidden attributes.
     */
    public function test_user_has_correct_hidden_attributes(): void
    {
        $user = new User();
        
        $this->assertEquals([
            'password',
            'remember_token',
        ], $user->getHidden());
    }

    /**
     * Test user has the correct casts.
     */
    public function test_user_has_correct_casts(): void
    {
        $user = new User();
        $casts = $user->getCasts();
        
        $this->assertArrayHasKey('email_verified_at', $casts);
        $this->assertArrayHasKey('password', $casts);
        $this->assertEquals('datetime', $casts['email_verified_at']);
        $this->assertEquals('hashed', $casts['password']);
    }

    /**
     * Test user can create tokens.
     */
    public function test_user_can_create_tokens(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token');
        
        $this->assertNotNull($token->plainTextToken);
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
            'name' => 'test-token',
        ]);
    }

    /**
     * Test user factory creates valid user.
     */
    public function test_user_factory_creates_valid_user(): void
    {
        $user = User::factory()->create();
        
        $this->assertNotNull($user->id);
        $this->assertNotNull($user->name);
        $this->assertNotNull($user->email);
        $this->assertNotNull($user->password);
    }
}
