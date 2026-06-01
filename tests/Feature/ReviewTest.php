<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['jwt.secret' => str_repeat('a', 32)]);
    }

    public function test_client_can_review_their_completed_order(): void
    {
        [$client, $order] = $this->completedOrder();

        $response = $this
            ->withToken(JWTAuth::fromUser($client))
            ->postJson('/api/reviews', [
                'order_id' => $order->id,
                'rating' => 5,
                'comment' => 'Pengerjaan rapi dan cepat.',
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.rating', 5);

        $this->assertDatabaseHas('reviews', [
            'order_id' => $order->id,
            'rating' => 5,
            'comment' => 'Pengerjaan rapi dan cepat.',
        ]);
    }

    public function test_client_cannot_review_an_unfinished_order(): void
    {
        [$client, $order] = $this->completedOrder(['status' => 'in_progress']);

        $response = $this
            ->withToken(JWTAuth::fromUser($client))
            ->postJson('/api/reviews', [
                'order_id' => $order->id,
                'rating' => 4,
            ]);

        $response
            ->assertStatus(422)
            ->assertJsonPath('success', false);

        $this->assertDatabaseMissing('reviews', [
            'order_id' => $order->id,
        ]);
    }

    public function test_client_cannot_review_another_clients_order(): void
    {
        [, $order] = $this->completedOrder();
        $otherClient = User::factory()->create(['role' => 'client']);

        $response = $this
            ->withToken(JWTAuth::fromUser($otherClient))
            ->postJson('/api/reviews', [
                'order_id' => $order->id,
                'rating' => 4,
            ]);

        $response
            ->assertForbidden()
            ->assertJsonPath('success', false);
    }

    public function test_order_can_only_have_one_review(): void
    {
        [$client, $order] = $this->completedOrder();
        Review::create([
            'order_id' => $order->id,
            'rating' => 5,
            'comment' => 'Review pertama.',
        ]);

        $response = $this
            ->withToken(JWTAuth::fromUser($client))
            ->postJson('/api/reviews', [
                'order_id' => $order->id,
                'rating' => 3,
            ]);

        $response
            ->assertStatus(422)
            ->assertJsonPath('success', false);

        $this->assertDatabaseCount('reviews', 1);
    }

    public function test_service_reviews_can_be_listed_publicly(): void
    {
        [, $order] = $this->completedOrder();
        Review::create([
            'order_id' => $order->id,
            'rating' => 5,
            'comment' => 'Mantap.',
        ]);

        $response = $this->getJson("/api/services/{$order->service_id}/reviews");

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.data.0.rating', 5)
            ->assertJsonPath('data.data.0.comment', 'Mantap.');
    }

    /**
     * @return array{0: User, 1: Order}
     */
    private function completedOrder(array $overrides = []): array
    {
        $freelancer = User::factory()->create(['role' => 'freelancer']);
        $client = User::factory()->create(['role' => 'client']);
        $category = Category::create([
            'name' => 'Pemrograman',
            'slug' => 'pemrograman',
        ]);
        $service = Service::create([
            'user_id' => $freelancer->id,
            'category_id' => $category->id,
            'title' => 'Bikin Website',
            'description' => 'Landing page dan dashboard sederhana.',
            'price' => 250000,
            'status' => 'active',
        ]);
        $order = Order::create(array_merge([
            'service_id' => $service->id,
            'client_id' => $client->id,
            'status' => 'completed',
            'note' => 'Butuh cepat.',
        ], $overrides));

        return [$client, $order];
    }
}
