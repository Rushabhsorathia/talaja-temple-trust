<?php

namespace Tests\Feature;

use App\Models\DonationCategory;
use App\Models\RoomType;
use App\Models\Room;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DevoteePortalTest extends TestCase
{
    use RefreshDatabase;

    protected function verifiedUser(): User
    {
        return User::factory()->create([
            'email_verified_at' => now(),
            'type' => 'devotee',
            'is_active' => true,
        ]);
    }

    public function test_donation_index_loads(): void
    {
        $this->get('/donate')->assertStatus(200);
    }

    public function test_donation_store_creates_pending_donation(): void
    {
        $cat = DonationCategory::factory()->create();

        $response = $this->post('/donate', [
            'donation_category_id' => $cat->id,
            'amount' => 501,
            'is_80g' => false,
            'donor_name' => 'Test Donor',
            'donor_email' => 'donor@example.com',
            'donor_mobile' => '9876543210',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('donations', [
            'donor_name' => 'Test Donor',
            'amount' => 501,
            'status' => 'pending',
        ]);
    }

    public function test_donation_requires_valid_amount(): void
    {
        $this->post('/donate', ['amount' => 0])->assertSessionHasErrors('amount');
    }

    public function test_room_booking_creates_booking(): void
    {
        $type = RoomType::factory()->create(['tariff' => 300]);
        Room::factory()->create(['room_type_id' => $type->id]);

        $response = $this->actingAs($this->verifiedUser())->post('/bookings/room', [
            'room_type_id' => $type->id,
            'check_in' => now()->addDay()->toDateString(),
            'check_out' => now()->addDays(3)->toDateString(),
            'guests' => 2,
            'guest_name' => 'Guest One',
            'guest_mobile' => '9876543210',
            'payment_mode' => 'pay_at_temple',
        ]);

        $response->assertRedirect('/bookings/my');
        $this->assertDatabaseHas('room_bookings', ['guest_name' => 'Guest One']);
    }

    public function test_shop_add_to_cart_and_checkout(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        $this->actingAs($this->verifiedUser())->post('/shop/add', ['product_id' => $product->id, 'qty' => 2])->assertRedirect();
        $this->get('/shop/cart')->assertStatus(200);

        $this->post('/shop/checkout', [
            'customer_name' => 'Buyer',
            'customer_mobile' => '9876543210',
            'shipping_address' => '123 Temple Rd',
        ])->assertRedirect('/shop/orders');

        $this->assertDatabaseHas('orders', ['customer_name' => 'Buyer']);
    }
}
