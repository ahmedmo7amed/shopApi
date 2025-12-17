<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Product\Models\Product;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_list_products()
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'name']
                     ]
                 ]);
    }

    /** @test */
    public function can_search_products()
    {
        Product::factory()->create(['name' => 'iPhone']);

        $response = $this->getJson('/api/products/search?query=iPhone');

        $response->assertStatus(200);
    }
}
