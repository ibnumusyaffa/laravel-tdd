<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_register_success()
    {
        $payload = [
            'name' => 'john',
            'email' => 'john@toptal.com',
            'password' => 'toptal123',
            'password_confirmation' => 'toptal123'
        ];

        $this->json('post', '/api/register', $payload)
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                    'api_token',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'john',
            'email' => 'john@toptal.com',
        ]);
    }

    public function test_require_pass_email_name()
    {
        $this->json('post', '/api/register')
            ->assertStatus(422)
            ->assertJson(['errors' => [
                'name' => ["The name field is required."],
                'email' => ["The email field is required."],
                'password' => ["The password field is required."],
            ]])
            ->assertJsonStructure(['message', 'errors' => [
                'name',
                'email',
                'password'
            ]]);

    }

    public function test_password_confirm()
    {
        $payload = [
            'name' => 'john',
            'email' => 'john@toptal.com',
            'password' => 'toptal123',
        ];


        $this->json('post', '/api/register', $payload)
            ->assertStatus(422)
            ->assertJsonStructure(['message', 'errors' => [
                'password'
            ]]);
    }
    

}

