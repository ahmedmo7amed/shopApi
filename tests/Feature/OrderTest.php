<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_orders()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/orders');

        $response->assertStatus(200);
    }
}
