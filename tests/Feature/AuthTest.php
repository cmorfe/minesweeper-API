<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\ApiTestTrait;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class AuthTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * Register
     */
    public function testRequiredFieldsForRegistration()
    {
        $this->json('POST', 'api/v1/register', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "username" => ["The username field is required."],
                    "password" => ["The password field is required."],
                ]
            ]);
    }

    public function testRepeatPassword()
    {
        $userData = [
            "username" => "cmorfe",
            "password" => "123456"
        ];

        $this->json('POST', 'api/v1/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "password" => ["The password confirmation does not match."]
                ]
            ]);
    }

    public function testSuccessfulRegistration()
    {
        $userData = [
            "username" => "cmorfe",
            "password" => "123456",
            "password_confirmation" => "123456"
        ];

        $this->json('POST', 'api/v1/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                "message" => "Register successful.",
            ])
            ->assertJsonStructure([
                "data" => [
                    'access_token'
                ]
            ]);
    }

    public function testUsernameAlreadyExists()
    {
        $userData = [
            "username" => "cmorfe",
            "password" => "123456",
            "password_confirmation" => "123456"
        ];

        User::create($userData);

        $this->json('POST', 'api/v1/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "username" => ["The username has already been taken."]
                ]
            ]);
    }

    /**
     * Login
     */
    public function testMustEnterEmailAndPassword()
    {
        $this->json('POST', 'api/v1/login')
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    'username' => ["The username field is required."],
                    'password' => ["The password field is required."],
                ]
            ]);
    }

    public function testSuccessfulLogin()
    {
        $credentials = [
            "username" => "cmorfe",
            "password" => "123456"
        ];

        User::factory()->create([
            "username" => $credentials['username'],
            'password' => bcrypt($credentials['password']),
        ]);

        $this->json('POST', 'api/v1/login', $credentials, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "message" => "Login successful.",
            ])
            ->assertJsonStructure([
                "data" => [
                    'access_token'
                ]
            ]);

        $this->assertAuthenticated();
    }

    public function testFailedLogin()
    {
        $credentials = [
            "username" => "cmorfe",
            "password" => "123456"
        ];

        $this->json('POST', 'api/v1/login', $credentials, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "message" => trans('auth.failed'),
            ]);
    }

    /**
     * Logout
     */
    public function testSuccessfulLogout()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $this->json('POST', 'api/v1/logout', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "message" => "Logout successful.",
            ]);

    }
}
