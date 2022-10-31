<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function item_can_be_added_to_the_cart()
    {
        $this->post('/add_to_cart', [
            'id' => 1,
        ])
        ->assertRedirect('/')
        ->assertSessionHasNoErrors()
        ->assertSessionHas('cart.0', [
            'id' => 1,
            'qty' => 1,
        ]);
    }

    /** @test */
    public function items_added_to_the_cart_can_be_seen_in_the_cart_page()
    {
        $this->withoutExceptionHandling();

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

        $this->post('/add_to_cart', [
            'id' => 1, // Scorpions
        ]);
        $this->post('/add_to_cart', [
            'id' => 3, // The Beatles
        ]);

        $cart_items = [
            [
                'id' => 1,
                'qty' => 1,
                'name' => 'Scorpions',
                'image' => 'https://vnadiradze.ge/info/laravel/images/vinyl.png',
                'cost' => 1.5,
                'subtotal' => 1.5
            ],
            [
                'id' => 3,
                'qty' => 1,
                'name' => 'The Beatles',
                'image' => 'https://vnadiradze.ge/info/laravel/images/vinyl.png',
                'cost' => 3.2,
                'subtotal' => 3.2
            ],
        ];

        $this->get('/cart')
            ->assertViewHas('cart_items', $cart_items)
            ->assertSeeTextInOrder([
                'Scorpions',
                'The Beatles',
            ])
            ->assertDontSeeText('Metallica');
    }

    /** @test */
    public function item_can_be_removed_from_the_cart()
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

        // აქვე დავამატოთ ვინილები სესიაში
        session(['cart' => [
            ['id' => 2, 'qty' => 1], // Metallica
            ['id' => 3, 'qty' => 3], // The Beatles
        ]]);

        $this->delete('/cart/2') // წავშალოთ Metallica
            ->assertRedirect('/cart')
            ->assertSessionHasNoErrors()
            ->assertSessionHas('cart', [
                ['id' => 3, 'qty' => 3]
        ]);

        // გადავამოწმოთ გამოდის თუ არა კალათის გვერდზე მოსალოდნელი ინფორმაცია
        $this->get('/cart')
            ->assertSeeInOrder([
                'The Beatles', // ვინილის დასახელება
                '3', // რაოდენობა
                '3.2 ₾', // ფასი
            ])
            ->assertDontSeeText('Metallica');
    }

    /** @test */
    public function cart_item_qty_can_be_updated()
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

        // დავამატოთ პრდუქტი კალათში
        session(['cart' => [
            ['id' => 1, 'qty' => 1], // Scorpions
            ['id' => 3, 'qty' => 1], // The Beatles
        ]]);

        $this->patch('/cart/3', [ // განვაახლოთ The Beatles-ის რაოდენობა
            'qty' => 5,
        ])
        ->assertRedirect('/cart')
        ->assertSessionHasNoErrors()
        ->assertSessionHas('cart', [
            ['id' => 1, 'qty' => 1],
            ['id' => 3, 'qty' => 5],
        ]);

        // დავადასტუროთ, რომ კალათის გვერდზე სწორი ინფორმაცია ჩანს
        $this->get('/cart')
            ->assertSeeInOrder([
                'Scorpions',
                '1', // რაოდენობა
                '1.5 ₾', // ფასი

                'The Beatles',
                '5', // რაოდენობა
                '3.2 ₾', // ფასი
            ]);
    }
}

