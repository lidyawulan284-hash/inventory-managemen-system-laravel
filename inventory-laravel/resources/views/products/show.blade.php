@extends('layouts.app')

@section('title', 'Detail Produk: ' . $product->name)
@section('page_title', 'Detail Produk')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Produk</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $product->sku }}</li>
@endsection

@section('content')
<div class="row g-4">
    <!-- Kolom Kiri: Spesifikasi & Data Produk -->
    <div class="col-lg-5">
        <div class="card shadow-sm h-100">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-info-circle me-1 text-primary"></i> Data Master Produk</span>
                @if(Auth::user() && Auth::user()->isAdmin())
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                @endif
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border fs-6">
                        {{ $product->category->name }}
                    </span>
                    <h4 class="fw-bold text-dark mt-2 mb-1">{{ $product->name }}</h4>
                    <div class="text-muted"><code>{{ $product->sku }}</code></div>
                </div>

                <div class="p-3 bg-light rounded-3 mb-3 border">
                    <div class="row g-2 text-center">
                        <div class="col-6 border-end">
                            <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.72rem;">Harga Satuan</small>
                            <h5 class="fw-bold text-primary mb-0 mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</h5>
                        </div>
                        <div class="col-6">
                            <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.72rem;">Stok Saat Ini</small>
                            <h5 class="fw-bold mb-0 mt-1 {{ $product->isLowStock() ? 'text-danger' : 'text-success' }}">
                                {{ $product->stock }} unit
                            </h5>
                        </div>
                    </div>
                </div>

                <table class="table table-sm table-borderless mb-3" style="font-size: 0.88rem;">
                    <tr>
                        <th class="text-muted" style="width: 40%;">Ambang Minimum:</th>
                        <td>{{ $product->minimum_stock }} unit</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Status Persediaan:</th>
                        <td>
                            @if($product->isLowStock())
                                <span class="badge badge-low-stock px-2 py-1">
                                    <i class="bi bi-exclamation-triangle-fill"></i> Stok Menipis (Di bawah minimum)
                                </span>
                            @else
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                    <i class="bi bi-check-circle-fill"></i> Stok Aman
                                </span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Nilai Stok Total:</th>
                        <td class="fw-semibold text-dark">
                            Rp {{ number_format($product->stock * $product->price, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Deskripsi:</th>
                        <td>{{ $product->description ?: 'Tidak ada deskripsi.' }}</td>
                    </tr>
                </table>

                <!-- Many-to-Many Suppliers List -->
                <div class="pt-3 border-top">
                    <div class="fw-bold text-dark mb-2" style="font-size: 0.88rem;">
                        <i class="bi bi-truck me-1 text-secondary"></i> Pemasok Terdaftar (Many-to-Many):
                    </div>
                    @forelse($product->suppliers as $sup)
                        <div class="d-flex justify-content-between align-items-center p-2 mb-1 bg-white rounded border" style="font-size: 0.84rem;">
                            <div>
                                <strong>{{ $sup->name }}</strong>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $sup->phone ?: '-' }} &bull; {{ $sup->email ?: '-' }}</div>
                            </div>
                            @if($sup->pivot->supplier_price)
                                <span class="badge bg-light text-dark border">
                                    Beli: Rp {{ number_format($sup->pivot->supplier_price, 0, ',', '.') }}
                                </span>
                            @endif
                        </div>
                    @empty
                        <div class="text-muted small">Belum dikaitkan dengan supplier manapun.</div>
                    @endforelse
                </div>

                <div class="d-flex gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('stock-movements.create', ['product_id' => $product->id, 'type' => 'IN']) }}" class="btn btn-success btn-sm flex-grow-1">
                        <i class="bi bi-box-arrow-in-down"></i> + Barang Masuk
                    </a>
                    <a href="{{ route('stock-movements.create', ['product_id' => $product->id, 'type' => 'OUT']) }}" class="btn btn-danger btn-sm flex-grow-1">
                        <i class="bi bi-box-arrow-up"></i> - Barang Keluar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Histori Mutasi Stok Produk Ini -->
    <div class="col-lg-7">
        <div class="card shadow-sm h-100">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-clock-history me-1 text-primary"></i> Riwayat Mutasi Stok Produk</span>
                <span class="badge bg-secondary">{{ $product->stockMovements->count() }} Transaksi</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Tipe</th>
                                <th>Jumlah</th>
                                <th>Petugas</th>
                                <th>Referensi & Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($product->stockMovements as $movement)
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $movement->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $movement->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($movement->type === 'IN')
                                            <span class="badge badge-stock-in px-2 py-1"><i class="bi bi-plus-lg"></i> MASUK</span>
                                        @else
                                            <span class="badge badge-stock-out px-2 py-1"><i class="bi bi-dash-lg"></i> KELUAR</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold {{ $movement->type === 'IN' ? 'text-success' : 'text-danger' }}">
                                        {{ $movement->type === 'IN' ? '+' : '-' }}{{ $movement->quantity }} unit
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $movement->user->name }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $movement->reference_number ?: '-' }}</div>
                                        <div class="text-muted small">{{ $movement->notes ?: '-' }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada transaksi mutasi stok untuk produk ini.</td>
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
