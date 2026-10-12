<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventorySystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test authentication: login with username or email
     */
    public function test_user_can_login_with_valid_credentials()
    {
        $response = $this->post('/login', [
            'login' => 'admin',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }

    /**
     * Test authentication failure
     */
    public function test_user_cannot_login_with_invalid_credentials()
    {
        $response = $this->post('/login', [
            'login' => 'admin',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    /**
     * Test dashboard view loads for authenticated user
     */
    public function test_authenticated_user_can_view_dashboard()
    {
        $user = User::where('role', 'admin')->first();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Ringkasan Dashboard');
        $response->assertSee('Total Produk');
    }

    /**
     * Test Authorization: Staff cannot access user administration
     */
    public function test_staff_cannot_access_user_management()
    {
        $staff = User::where('role', 'staff')->first();

        $response = $this->actingAs($staff)->get('/users');

        $response->assertStatus(403);
    }

    /**
     * Test Authorization: Admin can access user management
     */
    public function test_admin_can_access_user_management()
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/users');

        $response->assertStatus(200);
        $response->assertSee('Manajemen Pengguna Sistem');
    }

    /**
     * Test Product search, filter, and sorting
     */
    public function test_product_search_and_filter()
    {
        $user = User::first();

        // Search by keyword
        $response = $this->actingAs($user)->get('/products?search=Keyboard');
        $response->assertStatus(200);
        $response->assertSee('Mechanical Keyboard RGB TKL');

        // Low stock filter
        $response = $this->actingAs($user)->get('/products?low_stock=1');
        $response->assertStatus(200);
        $response->assertSee('Laptop Business Ultrabook 14 Inch i5');
    }

    /**
     * Test Safe Stock In Transaction (DB Transaction & Stock increment)
     */
    public function test_stock_in_transaction_increases_product_stock()
    {
        $user = User::where('role', 'staff')->first();
        $product = Product::first();
        $initialStock = $product->stock;
        $qtyToAdd = 15;

        $response = $this->actingAs($user)->post('/stock-movements', [
            'product_id' => $product->id,
            'type' => 'IN',
            'quantity' => $qtyToAdd,
            'reference_number' => 'TEST-IN-001',
            'notes' => 'Penambahan stok unit uji',
        ]);

        $response->assertRedirect('/stock-movements');
        $response->assertSessionHas('success');

        $product->refresh();
        $this->assertEquals($initialStock + $qtyToAdd, $product->stock);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'user_id' => $user->id,
            'type' => 'IN',
            'quantity' => $qtyToAdd,
            'reference_number' => 'TEST-IN-001',
        ]);
    }

    /**
     * Test Safe Stock Out Transaction (DB Transaction & Stock decrement)
     */
    public function test_stock_out_transaction_decreases_product_stock()
    {
        $user = User::where('role', 'staff')->first();
        $product = Product::where('stock', '>=', 5)->first();
        $initialStock = $product->stock;
        $qtyToDeduct = 3;

        $response = $this->actingAs($user)->post('/stock-movements', [
            'product_id' => $product->id,
            'type' => 'OUT',
            'quantity' => $qtyToDeduct,
            'reference_number' => 'TEST-OUT-001',
            'notes' => 'Pengeluaran stok unit uji',
        ]);

        $response->assertRedirect('/stock-movements');
        $response->assertSessionHas('success');

        $product->refresh();
        $this->assertEquals($initialStock - $qtyToDeduct, $product->stock);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'user_id' => $user->id,
            'type' => 'OUT',
            'quantity' => $qtyToDeduct,
            'reference_number' => 'TEST-OUT-001',
        ]);
    }

    /**
     * Test Stock Out validation blocks negative stock
     */
    public function test_stock_out_fails_when_quantity_exceeds_available_stock()
    {
        $user = User::where('role', 'staff')->first();
        $product = Product::first();
        $initialStock = $product->stock;
        $excessiveQuantity = $initialStock + 100;

        $response = $this->actingAs($user)->post('/stock-movements', [
            'product_id' => $product->id,
            'type' => 'OUT',
            'quantity' => $excessiveQuantity,
            'reference_number' => 'TEST-FAIL-001',
            'notes' => 'Percobaan pengeluaran stok melebihi batas',
        ]);

        $response->assertSessionHasErrors('quantity');

        // Stock must not change!
        $product->refresh();
        $this->assertEquals($initialStock, $product->stock);

        // Transaction must not be logged!
        $this->assertDatabaseMissing('stock_movements', [
            'reference_number' => 'TEST-FAIL-001',
        ]);
    }

    /**
     * Test category deletion restriction when products exist
     */
    public function test_category_with_products_cannot_be_deleted()
    {
        $admin = User::where('role', 'admin')->first();
        $categoryWithProducts = Category::has('products')->first();

        $response = $this->actingAs($admin)->delete("/categories/{$categoryWithProducts->id}");

        $response->assertRedirect('/categories');
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $categoryWithProducts->id]);
    }

    /**
     * Test product deletion restriction when stock movements exist
     */
    public function test_product_with_movements_cannot_be_deleted()
    {
        $admin = User::where('role', 'admin')->first();
        $productWithMovements = Product::has('stockMovements')->first();

        $response = $this->actingAs($admin)->delete("/products/{$productWithMovements->id}");

        $response->assertRedirect('/products');
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('products', ['id' => $productWithMovements->id]);
    }
}
