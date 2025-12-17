<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use Modules\Product\Models\Product;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_access_cart()
    {
        $response = $this->getJson('/api/cart');

        $response->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_can_add_to_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson("/api/cart/add/{$product->id}");

        $response->assertStatus(200);
    }
}
