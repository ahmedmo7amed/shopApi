<?php

namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use Modules\Product\Models\Product;
class WishlistTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_access_wishlist()
    {
        $response = $this->getJson('/api/wishlist');

        $response->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_can_toggle_wishlist_item()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson("/api/wishlist/toggle/{$product->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure(['message', 'in_wishlist']);
    }
}
