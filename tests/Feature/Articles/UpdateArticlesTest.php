<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateArticlesTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function guest_users_cannot_update_articles()
    {
       $article = Article::factory()->create();

       $this->jsonApi()->patch(route('api.v1.articles.update',$article))
       ->assertStatus(401);
    }

    /** @test */
    public function authenticated_users_can_update_articles()
    {
       $article = Article::factory()->create();

       Sanctum::actingAs($article->user);

       $this->jsonApi()->withData([
           'type' => 'articles',
           'id' => $article->getRouteKey(),
           'attributes' =>[
               'title' => 'Title Changed',
               'slug' => 'title-changed',
               'content' => 'Content Changed',
           ]
       ])->patch(route('api.v1.articles.update',$article))
       ->assertStatus(200);

       $this->assertDatabaseHas('articles',[
           'title' => 'Title Changed',
           'slug' => 'title-changed',
           'content' => 'Content Changed',
       ]);
    }

    /** @test */
    public function can_update_the_title_only()
    {
       $article = Article::factory()->create();

       Sanctum::actingAs($article->user);

       $this->jsonApi()->withData([
           'type' => 'articles',
           'id' => $article->getRouteKey(),
           'attributes' =>[
               'title' => 'Title Changed',
           ]
       ])->patch(route('api.v1.articles.update',$article))
       ->assertStatus(200);

       $this->assertDatabaseHas('articles',[
           'title' => 'Title Changed',
       ]);
    }

    /** @test */
    public function can_update_the_slug_only()
    {
       $article = Article::factory()->create();

       Sanctum::actingAs($article->user);

       $this->jsonApi()->withData([
           'type' => 'articles',
           'id' => $article->getRouteKey(),
           'attributes' =>[
               'slug' => 'slug-changed',
           ]
       ])->patch(route('api.v1.articles.update',$article))
       ->assertStatus(200);

       $this->assertDatabaseHas('articles',[
           'slug' => 'slug-changed',
       ]);
    }
}
