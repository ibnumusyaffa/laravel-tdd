<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;

class LoginTest extends TestCase
{
    public function test_require_email_and_login()
    {
        $this->json('POST', 'api/login')
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email',
                    'password',
                ],
                
            ]);
    }

    public function test_user_login_succesfully()
    {
        $user = factory(User::class)->create([
            'email' => 'testlogin@user.com',
            'password' => bcrypt('toptal')
        ]);

        $payload = [
            'email' => 'testlogin@user.com',
            'password' => 'toptal'
        ];

        $this->json('POST', 'api/login', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                    'api_token'
                ]
            ]);

    }




}
