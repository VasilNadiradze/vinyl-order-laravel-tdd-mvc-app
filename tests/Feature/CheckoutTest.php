<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cart_items_can_be_seen_from_the_checkout_page()
    {
        Product::factory()->create([
            'name' => 'Scorpions',
            'cost' => 1.5,
        ]);
        Product::factory()->create([
            'name' => 'Metallica',
            'cost' => 2.1,
        ]);
        Product::factory()->create([
            'name' => 'The Beatles',
            'cost' => 3.2,
        ]);

        session([
            'cart' => [
                ['id' => 2, 'qty' => 1], // Metallica
                ['id' => 3, 'qty' => 2], // The Beatles
            ],
        ]);

        $checkout_items = [
            [
                'id' => 2,
                'qty' => 1,
                'name' => 'Metallica',
                'image' => 'https://vnadiradze.ge/info/laravel/images/vinyl.png',
                'cost' => 2.1,
                'subtotal' => 2.1
            ],
            [
                'id' => 3,
                'qty' => 2,
                'name' => 'The Beatles',
                'image' => 'https://vnadiradze.ge/info/laravel/images/vinyl.png',
                'cost' => 3.2,
                'subtotal' => 6.4
            ],
        ];

        $this->get('/checkout')
            ->assertViewIs('checkout')
            ->assertViewHas('checkout_items', $checkout_items)
            ->assertSeeTextInOrder([
                'Metallica',
                '2.1 ₾',
                '1x',
                '2.1 ₾',

                'The Beatles',
                '3.2 ₾',
                '2x',
                '6.4 ₾',

                '8.5 ₾', // ჯამი
            ]);
    }

    /** @test */
    public function order_can_be_created()
    {
        Product::factory()->create([
            'name' => 'Scorpions',
            'cost' => 1.5,
        ]);

        session([
            'cart' => [
                ['id' => 1, 'qty' => 2]
            ],
        ]);

        $this->post('/checkout')->assertSessionHasNoErrors();

        // გადავამოწმოთ ნამდვილად შეიქმნა თუ არა ჩანაწრი ბაზაში
        $this->assertDatabaseHas('orders', [
            'total' => 3, // 2 x 1.5
        ]);

        $this->assertDatabaseHas('order_details', [
            'order_id' => 1,
            'product_id' => 1,
            'cost' => 1.5,
            'qty' => 2,
        ]);
    }
}
          