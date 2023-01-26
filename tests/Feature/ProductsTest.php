<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_products_homepage_have_no_data()
    {
        $response = $this->actingAs($this->user)->get('/products');

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

        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);
        $response->assertSee($product->title);

        $response->assertViewHas('products', function($collection) use($product){
            return $collection->contains($product);
        });
    }

    public function test_paginated_products_table_should_not_contain_11th_record(){
        $products = Product::factory(11)->create();
        $lastProduct = $products->last();

        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);

        $response->assertViewHas('products', function($collection) use($lastProduct){
            return !$collection->contains($lastProduct);
        });
    }

    public function test_admin_can_see_products_create_button(){
        $response = $this->actingAs($this->admin)->get('/products');

        $response->assertStatus(200);
        $response->assertSee('Add new product');
    }

    public function test_admin_can_not_see_products_create_button(){
        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);
        $response->assertDontSee('Add new product');
    }

    public function test_admin_can_access_products_create_page(){
        $response = $this->actingAs($this->admin)->get('/products/create');

        $response->assertStatus(200);
    }

    public function test_none_admin_can_not_access_products_create_page(){
        $response = $this->actingAs($this->user)->get('/products/create');

        $response->assertStatus(403);
    }
}
