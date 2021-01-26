<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IncludeAuthors extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function can_include_authors()
    {
       $article = Article::factory()->create();

       $this->jsonApi()
           ->includePaths('authors')
           ->get(route('api.v1.articles.read',$article))
           ->assertSee($article->user->name);
    }
}
