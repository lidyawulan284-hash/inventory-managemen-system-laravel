@extends('layouts.app')

@section('title', 'Detail Supplier: ' . $supplier->name)
@section('page_title', 'Detail Supplier')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}" class="text-decoration-none">Supplier</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $supplier->name }}</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-truck me-1 text-primary"></i> Data Pemasok</span>
                @if(Auth::user() && Auth::user()->isAdmin())
                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                @endif
            </div>
            <div class="card-body">
                <h4 class="fw-bold text-dark mb-3">{{ $supplier->name }}</h4>

                <div class="mb-3">
                    <label class="text-muted small text-uppercase fw-semibold d-block">Nomor Telepon</label>
                    <div class="fw-semibold">{{ $supplier->phone ?: '-' }}</div>
                </div>

                <div class="mb-3">
                    <label class="text-muted small text-uppercase fw-semibold d-block">Email</label>
                    <div class="fw-semibold">{{ $supplier->email ?: '-' }}</div>
                </div>

                <div class="mb-3">
                    <label class="text-muted small text-uppercase fw-semibold d-block">Alamat</label>
                    <div>{{ $supplier->address ?: '-' }}</div>
                </div>

                <div class="pt-3 border-top">
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1">
                        Menyuplai {{ $supplier->products->count() }} Jenis Produk
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm h-100">
            <div class="card-header py-3">
                <span class="fw-bold"><i class="bi bi-boxes me-1 text-primary"></i> Katalog Produk yang Disuplai (Relasi Many-to-Many)</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga Jual</th>
                                <th>Harga Pasokan</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($supplier->products as $product)
                                <tr>
                                    <td><code>{{ $product->sku }}</code></td>
                                    <td class="fw-semibold">{{ $product->name }}</td>
                                    <td>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border">
                                            {{ $product->category->name }}
                                        </span>
                                    </td>
                                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td>
                                        @if($product->pivot->supplier_price)
                                            <span class="badge bg-light text-dark border">
                                                Rp {{ number_format($product->pivot->supplier_price, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada produk yang dikaitkan dengan supplier ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
