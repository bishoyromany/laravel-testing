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

    public function test_create_product_successfully(){
        $product = [
            'title' => 'Product 123',
            'price' => 123
        ];

        $response = $this->actingAs($this->admin)->post('products', $product);

        $response->assertStatus(302);
        $response->assertRedirect('products');

        $this->assertDatabaseHas('products', $product);

        $lastProduct = Product::latest()->first();

        $this->assertEquals($product['title'], $lastProduct->title);
        $this->assertEquals($product['price'], $lastProduct->price);
    }

    public function test_update_product_validation_error_redirects_back_to_form(){
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('products.update', $product), [
            'title' => '',
            'price' => ''
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['title', 'price']);
        $response->assertInvalid(['title', 'price']);
    }

    public function test_update_product_successfully(){
        $product = Product::factory()->create();
        $updateProduct = [
            'title' => 'Update 1',
            'price' => 123,
            'id' => $product->id
        ];

        $response = $this->actingAs($this->admin)->put(route('products.update', $product), $updateProduct);

        $response->assertStatus(302);
        $response->assertRedirect(route('products.index'));
        $lastProduct = Product::latest()->first();

        $this->assertEquals($updateProduct['id'], $lastProduct->id);
        $this->assertEquals($updateProduct['title'], $lastProduct->title);
        $this->assertEquals($updateProduct['price'], $lastProduct->price);
    }

    public function test_delete_product_successfully(){
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('products.destroy', $product));

        $response->assertStatus(302);
        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseMissing('products', [$product]);

        $lastProduct = Product::latest()->first();
        $this->assertNotEquals($product->id, $lastProduct->id ?? 0);
    }
}
