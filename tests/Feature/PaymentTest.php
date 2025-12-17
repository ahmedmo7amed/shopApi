<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_process_payment()
    {
        $response = $this->postJson('/api/payment/process');

        $response->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_can_process_payment()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/payment/process', [
            'amount' => 100
        ]);

        $response->assertStatus(200);
    }
}
