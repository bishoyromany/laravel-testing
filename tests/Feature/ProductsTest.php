<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_products_homepage_have_no_data()
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertSee(__("Mo Products Found"));
    }

        /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_products_homepage_not_empty()
    {
        $product = Product::create([
            'title' => 'Test 1',
            'price' => 1.5
        ]);

        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertSee($product->title);

        $response->assertViewHas('products', function($collection) use($product){
            return $collection->contains($product);
        });
    }

    public function test_paginated_products_table_should_not_contain_11th_record(){
        $products = Product::factory(11)->create();
        $lastProduct = $products->last();

        $response = $this->get('/products');

        $response->assertStatus(200);

        $response->assertViewHas('products', function($collection) use($lastProduct){
            return !$collection->contains($lastProduct);
        });
    }
}
