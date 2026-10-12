<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockMovementController extends Controller
{
    /**
     * Display a listing of stock movements with search and filters
     */
    public function index(Request $request)
    {
        $products = Product::orderBy('name')->get();

        $query = StockMovement::with(['product.category', 'user'])
            ->when($request->filled('type'), function ($q) use ($request) {
                $q->filterByType($request->type);
            })
            ->when($request->filled('product_id'), function ($q) use ($request) {
                $q->filterByProduct($request->product_id);
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($sub) use ($search) {
                    $sub->where('reference_number', 'like', "%{$search}%")
                        ->orWhere('notes', 'like', "%{$search}%")
                        ->orWhereHas('product', function ($p) use ($search) {
                            $p->where('name', 'like', "%{$search}%")
                              ->orWhere('sku', 'like', "%{$search}%");
                        });
                });
            })
            ->latest();

        $movements = $query->paginate(15)->withQueryString();

        return view('stock_movements.index', compact('movements', 'products'));
    }

    /**
     * Show the form for creating a new stock movement (IN or OUT)
     */
    public function create(Request $request)
    {
        $products = Product::orderBy('name')->get();
        $selectedProductId = $request->query('product_id');
        $defaultType = strtoupper($request->query('type', 'IN'));

        if (! in_array($defaultType, ['IN', 'OUT'])) {
            $defaultType = 'IN';
        }

        return view('stock_movements.create', compact('products', 'selectedProductId', 'defaultType'));
    }

    /**
     * Store a stock transaction safely using DB::transaction and strict concurrency locking.
     * (Pertemuan 4 Slide 25, 26, 27)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type' => ['required', 'in:IN,OUT'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'product_id.required' => 'Pilih produk yang ingin ditransaksikan.',
            'product_id.exists' => 'Produk tidak ditemukan di database.',
            'type.required' => 'Tipe transaksi (Masuk / Keluar) wajib dipilih.',
            'type.in' => 'Tipe transaksi harus bernilai IN atau OUT.',
            'quantity.required' => 'Jumlah barang wajib diisi.',
            'quantity.integer' => 'Jumlah barang harus berupa angka bulat.',
            'quantity.min' => 'Jumlah barang harus minimal 1 unit (tidak boleh nol atau negatif).',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                // 1. SELECT ... FOR UPDATE (Pertemuan 4 Slide 26)
                // Lock row product untuk mencegah race condition / stok negatif
                $product = Product::lockForUpdate()->findOrFail($validated['product_id']);

                if ($validated['type'] === 'OUT') {
                    // 2. Semantic Validation: Barang keluar tidak boleh melebihi stok tersedia
                    if ($product->stock < $validated['quantity']) {
                        throw ValidationException::withMessages([
                            'quantity' => [
                                "Stok tidak mencukupi untuk barang keluar! " .
                                "Stok tersedia saat ini: {$product->stock} unit, sedangkan permintaan: {$validated['quantity']} unit."
                            ],
                        ]);
                    }

                    // 3. Update stock decrement
                    $product->stock -= $validated['quantity'];
                    $product->save();
                } else {
                    // Barang Masuk (IN): Tambah stok produk
                    $product->stock += $validated['quantity'];
                    $product->save();
                }

                // 4. Simpan riwayat transaksi ke stock_movements
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => $validated['type'],
                    'quantity' => $validated['quantity'],
                    'reference_number' => $validated['reference_number'] ? trim($validated['reference_number']) : null,
                    'notes' => $validated['notes'] ? trim($validated['notes']) : null,
                ]);
            });

            $typeLabel = $validated['type'] === 'IN' ? 'Barang Masuk (Stock In)' : 'Barang Keluar (Stock Out)';

            return redirect()->route('stock-movements.index')
                ->with('success', "Transaksi {$typeLabel} sebanyak {$validated['quantity']} unit berhasil disimpan dan stok produk telah diperbarui.");

        } catch (ValidationException $ve) {
            throw $ve;
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memproses transaksi inventaris: ' . $e->getMessage());
        }
    }
}
