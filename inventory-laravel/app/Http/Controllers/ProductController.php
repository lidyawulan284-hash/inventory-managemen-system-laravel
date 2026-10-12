<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of products with Search, Filter, Sorting, and Pagination.
     * (Pertemuan 4 Slide 16 & Pertemuan 5 Slide 38)
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $query = Product::with(['category', 'suppliers']);

        // Search by name or SKU
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->filterByCategory($request->category_id);
        }

        // Filter low stock products
        if ($request->boolean('low_stock')) {
            $query->lowStock();
        }

        // Safe sorting with allowlist
        $sortBy = $request->input('sort_by', 'name');
        $sortDir = $request->input('sort_dir', 'asc');
        $query->applySorting($sortBy, $sortDir);

        $products = $query->paginate(10)->withQueryString();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show form for creating a new product
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.create', compact('categories', 'suppliers'));
    }

    /**
     * Store a newly created product in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'suppliers' => ['nullable', 'array'],
            'suppliers.*' => ['exists:suppliers,id'],
        ], [
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'sku.required' => 'SKU (Kode Produk) wajib diisi.',
            'sku.unique' => 'SKU sudah digunakan oleh produk lain.',
            'name.required' => 'Nama produk wajib diisi.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.min' => 'Harga produk tidak boleh bernilai negatif.',
            'stock.min' => 'Jumlah stok awal tidak boleh negatif.',
            'minimum_stock.min' => 'Stok minimum tidak boleh negatif.',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $product = Product::create([
                'category_id' => $validated['category_id'],
                'sku' => strtoupper(trim($validated['sku'])),
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'minimum_stock' => $validated['minimum_stock'],
            ]);

            if ($request->has('suppliers')) {
                $product->suppliers()->sync($request->suppliers);
            }
        });

        return redirect()->route('products.index')
            ->with('success', 'Produk baru berhasil ditambahkan.');
    }

    /**
     * Display product detail and transaction history
     */
    public function show(Product $product)
    {
        $product->load(['category', 'suppliers', 'stockMovements.user' => function ($q) {
            $q->latest();
        }]);

        return view('products.show', compact('product'));
    }

    /**
     * Show form for editing product
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Update product in database
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'sku' => [
                'required',
                'string',
                'max:50',
                Rule::unique('products', 'sku')->ignore($product->id),
            ],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'suppliers' => ['nullable', 'array'],
            'suppliers.*' => ['exists:suppliers,id'],
        ], [
            'sku.required' => 'SKU wajib diisi.',
            'sku.unique' => 'SKU sudah digunakan oleh produk lain.',
            'price.min' => 'Harga tidak boleh bernilai negatif.',
            'stock.min' => 'Stok tidak boleh bernilai negatif.',
        ]);

        DB::transaction(function () use ($validated, $request, $product) {
            $product->update([
                'category_id' => $validated['category_id'],
                'sku' => strtoupper(trim($validated['sku'])),
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'minimum_stock' => $validated['minimum_stock'],
            ]);

            $product->suppliers()->sync($request->suppliers ?? []);
        });

        return redirect()->route('products.index')
            ->with('success', 'Data produk berhasil diperbarui.');
    }

    /**
     * Remove product (Protected against deleting products with transaction history)
     */
    public function destroy(Product $product)
    {
        // Business Rule (Pertemuan 4 Slide 33):
        // Produk yang memiliki histori tidak boleh dihapus sembarangan.
        if ($product->stockMovements()->exists()) {
            return redirect()->route('products.index')
                ->with('error', 'Produk "' . $product->name . '" tidak dapat dihapus karena memiliki riwayat pergerakan stok (stock movements). Hal ini untuk menjaga audit trail dan konsistensi data inventaris.');
        }

        DB::transaction(function () use ($product) {
            $product->suppliers()->detach();
            $product->delete();
        });

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
