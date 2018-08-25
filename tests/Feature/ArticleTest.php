<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Article;

class ArticleTest extends TestCase
{
    //create
    public function test_article_are_created_correctly()
    {
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        $payload = [
            'title' => 'Lorem',
            'body' => 'Ipsum'
        ];

        $this->json('POST', '/api/articles', $payload, $headers)
            ->assertStatus(201)
            ->assertJson($payload);

        $this->assertDatabaseHas('articles', $payload);
    }

    //read
    public function test_show_one_article()
    {
        $article_payload = [
            'title' => "first article",
            'body' => "fist body"
        ];

        $article = factory(Article::class)->create($article_payload);


        $this->json('GET', route('articles.show', $article->id))
            ->assertStatus(200)
            ->assertJson($article_payload);
    }

    //update
    public function test_article_update_correctly()
    {
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        
        $article_before_update = [
            'title' => "first article",
            'body' => "fist body"
        ];

        $article = factory(Article::class)->create($article_before_update);

        $this->assertDatabaseHas('articles', $article_before_update);

        $article_after_update = [
            'title' => "new title",
            'body' => "new body"
        ];
        $this->json('PUT', route('articles.update', $article->id), $article_after_update, $headers)
            ->assertStatus(200)
            ->assertJson($article_after_update);

        $this->assertDatabaseHas('articles', $article_after_update);
    }

    //delete
    public function test_delete_article()
    {
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        $article = factory(Article::class)->create([
            'title' => 'title deleted',
            'body' => 'body deleted',
        ]);
        // dd($article->toArray());

        $this->json('DELETE', route('articles.destroy', $article->id), [], $headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'error'
            ]);

        $this->assertDatabaseMissing('articles', [
            'title' => 'title deleted',
            'body' => 'body deleted',
        ]);
    }

    //list
    public function test_article_list()
    {
        factory(Article::class)->create([
            'title' => 'First Article',
            'body' => 'First Body'
        ]);

        factory(Article::class)->create([
            'title' => 'Second Article',
            'body' => 'Second Body'
        ]);

        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->json('GET', '/api/articles', [], $headers)
            ->assertStatus(200)
            ->assertJson([
                ['title' => 'First Article', 'body' => 'First Body'],
                ['title' => 'Second Article', 'body' => 'Second Body']
            ])
            ->assertJsonStructure([
                '*' => ['id', 'body', 'title', 'created_at', 'updated_at'],
            ]);
    }

    //show by id
    public function test_article_by_id()
    {
        $article = factory(Article::class)->create([
            'title' => 'First Article',
            'body' => 'First Body'
        ]);

        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];

        $this->json('GET',route('articles.show',$article->id),[],$headers)
            ->assertStatus(200)
            ->assertJson($article->toArray());
    }
}
