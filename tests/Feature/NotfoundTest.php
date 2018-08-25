<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class NotfoundTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->json("GET", "/asdf")
            ->assertStatus(404)
            ->assertJsonStructure([
                'error',
                'message'
            ]);

        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        $this->json('GET', route('articles.show', 120), [], $headers)
            ->assertStatus(404)
            ->assertJsonStructure([
                'error',
                'message'
            ]);
    }
}
