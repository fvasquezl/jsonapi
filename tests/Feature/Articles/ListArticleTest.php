<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_fetch_single_article()
    {
        $article = Article::factory()->create();

        $response = $this->jsonApi()->get(route('api.v1.articles.read', $article));
        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id' => (string)$article->getRouteKey(),
                'attributes' => [
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'content' => $article->content,
                    'createdAt' => $article->created_at,
                    'updatedAt' => $article->updated_at,
                ],
                'links' => [
                    'self' => route('api.v1.articles.read', $article)
                ]
            ]
        ]);
    }

    /** @test */
    public function can_fetch_all_articles()
    {
        $article = Article::factory()->times(3)->create();

        $response = $this->jsonApi()->get(route('api.v1.articles.index'));
        $response->assertJsonFragment([
            'data' => [
                [
                    'type' => 'articles',
                    'id' => (string)$article[0]->getRouteKey(),
                    'attributes' => [
                        'title' => $article[0]->title,
                        'slug' => $article[0]->slug,
                        'content' => $article[0]->content,
                        'createdAt' => $article[0]->created_at,
                        'updatedAt' => $article[0]->updated_at,
                    ],
                    'links' => [
                        'self' => route('api.v1.articles.read', $article[0])
                    ]
                ],
                [
                    'type' => 'articles',
                    'id' => (string)$article[1]->getRouteKey(),
                    'attributes' => [
                        'title' => $article[1]->title,
                        'slug' => $article[1]->slug,
                        'content' => $article[1]->content,
                        'createdAt' => $article[1]->created_at,
                        'updatedAt' => $article[1]->updated_at,
                    ],
                    'links' => [
                        'self' => route('api.v1.articles.read', $article[1])
                    ]
                ],
                [
                    'type' => 'articles',
                    'id' => (string)$article[2]->getRouteKey(),
                    'attributes' => [
                        'title' => $article[2]->title,
                        'slug' => $article[2]->slug,
                        'content' => $article[2]->content,
                        'createdAt' => $article[2]->created_at,
                        'updatedAt' => $article[2]->updated_at,
                    ],
                    'links' => [
                        'self' => route('api.v1.articles.read', $article[2])
                    ]
                ],
            ]
        ]);
    }
}
