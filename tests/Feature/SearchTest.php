<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function vinyl_search_page_is_accessible()
    {
        $this->get('/')->assertOk();
    }

    /** @test */
    public function vinyl_search_page_has_all_the_required_page_data()
    {
        // მომზადების ფაზა
        Product::factory()->count(3)->create(); // შევქმნათ 3 ვინილი

        // ქმედების ფაზა
        $response = $this->get('/');

        // მტკიცება
        $items = Product::get();

        $response->assertViewIs('search')->assertViewHas('items', $items);
    }

    /** @test */
    public function vinyl_search_page_shows_the_items()
    {
        Product::factory()->count(3)->create();

        $items = Product::get();

        $this->get('/')
            ->assertSeeInOrder([
                $items[0]->name,
                $items[1]->name,
                $items[2]->name,
            ]);
    }

    /** @test */
    public function vinyl_can_be_searched_given_a_query()
    {
        /*
         *  შევქმნათ სამი სხვადასხვა დასახელების მქონე ვინილი
         */

        Product::factory()->create([
            'name' => 'Metallica'
        ]);
        Product::factory()->create([
            'name' => 'Guns N roses'
        ]);
        Product::factory()->create([
            'name' => 'Pink floyd'
        ]);

        // მოვძებნოთ ერთ-ერთი მათგანის დასახელებით

        $this->get('/?query=metallica')
            ->assertSee('Metallica') // ეს უნდა დავინახოთ პასუხში
            ->assertDontSeeText('Guns N roses') // ეს ვერ უნდა დავინახოთ პასუხში
            ->assertDontSeeText('Pink floyd'); // ეს ვერ უნდა დავინახოთ პასუხში

        // ფილტრის გარეშე სამივე მათგანი უნდა დავინახოთ

        $this->get('/')->assertSeeInOrder([
            'Metallica',
            'Guns N roses',
            'Pink floyd'
        ]);
    }
}

