<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    public function test_login_with_valid_credentials()
    {
        $credentials = [
            'email' => 'john@email.com',
            'password' => 'holiday2024',
        ];

        $response = $this->postJson('/api/v1/login', $credentials);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'token',
                 ]);
    }

    public function test_login_with_invalid_credentials()
    {
        $credentials = [
            'email' => 'invalid@example.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/v1/login', $credentials);

        $response->assertStatus(401)
                 ->assertJson([
                     'invalidEmailOrPassword' => 'Invalid e-mail or password',
                 ]);
    }
}
