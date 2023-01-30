<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_returns_products_list(){
        $products = Product::factory(10)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200);

        $response->assertJson($products->toArray());
    }


    public function test_api_product_store_successfully(){
        $product = [
            'title' => 'Title1',
            'price' => 10
        ];

        $response = $this->postJson('/api/products', $product);

        $response->assertStatus(201);

        $response->assertJson($product);
    }

    public function test_api_product_invalid_store(){
        $product = [
            'title' => '',
            'price' => 10
        ];

        $response = $this->postJson('/api/products', $product);

        $response->assertStatus(422);
    }
}
