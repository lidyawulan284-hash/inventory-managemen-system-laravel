@extends('layouts.app')

@section('title', 'Daftar Produk')
@section('page_title', 'Master Data Produk')

@section('breadcrumb')
    <span class="mx-2 text-muted">/</span>
    <span class="text-dark fw-medium">Katalog Produk</span>
@endsection

@section('content')
<div class="card shadow-sm mb-4">
    <!-- Filter, Search, and Sort Toolbar -->
    <div class="card-body bg-light rounded-top border-bottom">
        <form action="{{ route('products.index') }}" method="GET" class="row g-2 align-items-center">
            <!-- Search -->
            <div class="col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Cari nama atau SKU..."
                           value="{{ request('search') }}">
                </div>
            </div>

            <!-- Filter Kategori -->
            <div class="col-md-3">
                <select name="category_id" class="form-select form-select-sm">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sorting Column & Direction -->
            <div class="col-md-2">
                <select name="sort_by" class="form-select form-select-sm">
                    <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Urut: Nama</option>
                    <option value="sku" {{ request('sort_by') == 'sku' ? 'selected' : '' }}>Urut: SKU</option>
                    <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Urut: Harga</option>
                    <option value="stock" {{ request('sort_by') == 'stock' ? 'selected' : '' }}>Urut: Stok</option>
                </select>
            </div>

            <div class="col-md-2">
                <select name="sort_dir" class="form-select form-select-sm">
                    <option value="asc" {{ request('sort_dir') == 'asc' ? 'selected' : '' }}>A - Z (Naik)</option>
                    <option value="desc" {{ request('sort_dir') == 'desc' ? 'selected' : '' }}>Z - A (Turun)</option>
                </select>
            </div>

            <!-- Submit & Reset Buttons -->
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary flex-grow-1">
                    <i class="bi bi-funnel"></i> Terapkan
                </button>
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset Filter">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
            </div>

            <!-- Checkbox Stok Rendah -->
            <div class="col-12 mt-2 pt-1 border-top border-secondary border-opacity-10 d-flex justify-content-between align-items-center">
                <div class="form-check form-check-inline mb-0">
                    <input class="form-check-input"
                           type="checkbox"
                           id="low_stock"
                           name="low_stock"
                           value="1"
                           {{ request('low_stock') ? 'checked' : '' }}
                           onchange="this.form.submit()">
                    <label class="form-check-label small fw-semibold text-danger" for="low_stock">
                        <i class="bi bi-exclamation-diamond-fill"></i> Tampilkan hanya produk dengan Stok Rendah (&le; Min Stok)
                    </label>
                </div>

                @if(Auth::user() && Auth::user()->isAdmin())
                <div>
                    <a href="{{ route('products.create') }}" class="btn btn-sm btn-success">
                        <i class="bi bi-plus-lg"></i> Tambah Produk Baru
                    </a>
                </div>
                @endif
            </div>
        </form>
    </div>

    <!-- Product Table -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 120px;">SKU</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga Satuan</th>
                        <th>Stok / Min</th>
                        <th>Supplier</th>
                        <th class="text-end" style="width: 190px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <code>{{ $product->sku }}</code>
                            </td>
                            <td>
                                <a href="{{ route('products.show', $product->id) }}" class="fw-semibold text-decoration-none text-dark">
                                    {{ $product->name }}
                                </a>
                                @if($product->description)
                                    <div class="text-muted text-truncate" style="max-width: 250px; font-size: 0.78rem;">
                                        {{ $product->description }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border">
                                    {{ $product->category->name }}
                                </span>
                            </td>
                            <td class="fw-semibold">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td>
                                @if($product->isLowStock())
                                    <span class="badge badge-low-stock px-2 py-1">
                                        <i class="bi bi-exclamation-triangle-fill"></i> {{ $product->stock }} unit (Min: {{ $product->minimum_stock }})
                                    </span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                        {{ $product->stock }} unit (Min: {{ $product->minimum_stock }})
                                    </span>
                                @endif
                            </td>
                            <td>
                                @forelse($product->suppliers as $sup)
                                    <span class="badge bg-light text-dark border me-1" style="font-size: 0.72rem;">
                                        {{ $sup->name }}
                                    </span>
                                @empty
                                    <span class="text-muted small">-</span>
                                @endforelse
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-secondary" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('stock-movements.create', ['product_id' => $product->id, 'type' => 'IN']) }}" class="btn btn-outline-success" title="Tambah Stok">
                                        <i class="bi bi-plus-lg"></i>
                                    </a>

                                    <a href="{{ route('stock-movements.create', ['product_id' => $product->id, 'type' => 'OUT']) }}" class="btn btn-outline-danger" title="Kurang Stok">
                                        <i class="bi bi-dash-lg"></i>
                                    </a>

                                    @if(Auth::user() && Auth::user()->isAdmin())
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-primary" title="Edit Produk">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus Produk">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada produk ditemukan dengan kriteria pencarian ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="card-footer bg-white py-3 d-flex justify-content-between align-items-center">
        <div class="small text-muted">
            Menampilkan {{ $products->firstItem() }} sampai {{ $products->lastItem() }} dari total {{ $products->total() }} produk
        </div>
        <div>
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
    @endif
</div>
@endsection
