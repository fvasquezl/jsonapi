<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListCategoriesTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function can_fetch_single_categories()
    {
        $category = Category::factory()->create();

        $response = $this->jsonApi()->get(route('api.v1.categories.read', $category));

        $response->assertJson([
            'data' => [
                'type' => 'categories',
                'id' => (string)$category->getRouteKey(),
                'attributes' => [
                    'name' => $category->name,
                    'slug' => $category->slug,
                ],
                'links' => [
                    'self' => route('api.v1.categories.read', $category)
                ]
            ]
        ]);
    }


    /** @test */
    public function can_fetch_all_categories()
    {
        $category = Category::factory()->times(3)->create();

        $response = $this->jsonApi()->get(route('api.v1.categories.index'));
        $response->assertJson([
            'data' => [
                [
                    'type' => 'categories',
                    'id' => (string)$category[0]->getRouteKey(),
                    'attributes' => [
                        'name' => $category[0]->name,
                        'slug' => $category[0]->slug,
                    ],
                    'links' => [
                        'self' => route('api.v1.categories.read', $category[0])
                    ]
                ],
                [
                    'type' => 'categories',
                    'id' => (string)$category[1]->getRouteKey(),
                    'attributes' => [
                        'name' => $category[1]->name,
                        'slug' => $category[1]->slug,
                    ],
                    'links' => [
                        'self' => route('api.v1.categories.read', $category[1])
                    ]
                ],
                [
                    'type' => 'categories',
                    'id' => (string)$category[2]->getRouteKey(),
                    'attributes' => [
                        'name' => $category[2]->name,
                        'slug' => $category[2]->slug,
                    ],
                    'links' => [
                        'self' => route('api.v1.categories.read', $category[2])
                    ]
                ],
            ]
        ]);
    }
}
