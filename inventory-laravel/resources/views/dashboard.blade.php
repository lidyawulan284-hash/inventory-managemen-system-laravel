@extends('layouts.app')

@section('title', 'Dashboard Inventory')
@section('page_title', 'Ringkasan Dashboard')

@section('breadcrumb')
    <span class="mx-2 text-muted">/</span>
    <span class="text-dark fw-medium">Dashboard</span>
@endsection

@section('content')
<!-- Welcome Banner Hero Card -->
<div class="card mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #ffffff; border-radius: 16px;">
    <div class="card-body p-4 p-md-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-10 text-white mb-2" style="font-size: 0.78rem;">
                    <i class="bi bi-shield-check text-success"></i>
                    <span>Database Relasional Terkoneksi &bull; Transaksi ACID Aman</span>
                </div>
                <h3 class="fw-bold mb-1 text-white">Selamat Datang, {{ Auth::user()->name }}! 👋</h3>
                <p class="mb-0 text-white-50" style="font-size: 0.92rem; max-width: 650px;">
                    Sistem mengelola <strong>{{ $totalProducts }} produk</strong> dalam <strong>{{ $totalCategories }} kategori</strong> dengan total <strong>{{ number_format($totalStockUnits) }} unit fisik</strong> tersimpan di gudang secara real-time.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    <a href="{{ route('stock-movements.create', ['type' => 'IN']) }}" class="btn btn-success btn-sm px-3 shadow-sm" style="border-radius: 10px;">
                        <i class="bi bi-box-arrow-in-down me-1"></i> + Catat Masuk
                    </a>
                    <a href="{{ route('stock-movements.create', ['type' => 'OUT']) }}" class="btn btn-danger btn-sm px-3 shadow-sm" style="border-radius: 10px;">
                        <i class="bi bi-box-arrow-up me-1"></i> - Catat Keluar
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-light btn-sm px-3" style="border-radius: 10px;">
                        <i class="bi bi-boxes me-1"></i> Katalog Produk
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 4 Top Executive Stat Cards -->
<div class="row g-3 mb-4">
    <!-- Stat 1: Total Produk -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden" style="border-top: 4px solid #4f46e5 !important;">
            <div class="card-body p-3 p-xl-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Katalog Produk</span>
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background-color: #eef2ff; color: #4f46e5;">
                        <i class="bi bi-boxes fs-4"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-dark">{{ number_format($totalProducts) }} <span class="fs-6 fw-normal text-muted">SKU</span></h3>
                <div class="d-flex align-items-center gap-1 text-muted" style="font-size: 0.82rem;">
                    <i class="bi bi-tags-fill text-indigo" style="color: #6366f1;"></i>
                    <span>Tersebar di <strong>{{ $totalCategories }}</strong> kategori</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 2: Total Nilai Inventaris -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden" style="border-top: 4px solid #10b981 !important;">
            <div class="card-body p-3 p-xl-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Valuasi Persediaan</span>
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background-color: #ecfdf5; color: #10b981;">
                        <i class="bi bi-cash-stack fs-4"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-1 text-dark" style="font-size: 1.45rem;">Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}</h4>
                <div class="d-flex align-items-center gap-1 text-muted" style="font-size: 0.82rem;">
                    <i class="bi bi-calculator text-success"></i>
                    <span>Kalkulasi <code>SUM(stok &times; harga)</code></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 3: Total Fisik & Mutasi Barang -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden" style="border-top: 4px solid #0284c7 !important;">
            <div class="card-body p-3 p-xl-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Fisik Unit Gudang</span>
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background-color: #e0f2fe; color: #0284c7;">
                        <i class="bi bi-box-seam fs-4"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-dark">{{ number_format($totalStockUnits) }} <span class="fs-6 fw-normal text-muted">Unit</span></h3>
                <div class="d-flex align-items-center gap-2 mt-1" style="font-size: 0.78rem;">
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-0">
                        +{{ number_format($totalStockIn) }} In
                    </span>
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-0">
                        -{{ number_format($totalStockOut) }} Out
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 4: Low Stock Alert -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden" style="border-top: 4px solid #f59e0b !important;">
            <div class="card-body p-3 p-xl-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Peringatan Stok</span>
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background-color: #fef3c7; color: #b45309;">
                        <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-dark">{{ number_format($lowStockCount) }} <span class="fs-6 fw-normal text-muted">Produk</span></h3>
                <div class="mt-1">
                    @if($lowStockCount > 0)
                        <span class="badge badge-low-stock px-2 py-1">
                            <i class="bi bi-bell-fill"></i> Perlu Restock Segera
                        </span>
                    @else
                        <span class="badge badge-safe-stock px-2 py-1 text-success">
                            <i class="bi bi-check-circle-fill"></i> Semua Stok Terpenuhi
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Row: Transaksi Mutasi (Left) + Widgets (Right) -->
<div class="row g-4 align-items-stretch">
    <!-- Kolom Kiri: Riwayat Mutasi Stok Terbaru -->
    <div class="col-xl-8 col-lg-7 d-flex">
        <div class="card shadow-sm w-100 d-flex flex-column" style="min-width: 0;">
            <!-- Header Card -->
            <div class="card-header py-3 px-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-2 p-2 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                        <i class="bi bi-clock-history fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark mb-0" style="font-size: 0.98rem;">Transaksi Mutasi Stok Terbaru</div>
                        <small class="text-muted" style="font-size: 0.78rem;">6 catatan pergerakan inventaris terakhir</small>
                    </div>
                </div>
                <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-primary btn-sm px-3 d-flex align-items-center gap-1" style="font-size: 0.8rem;">
                    <span>Lihat Semua</span>
                    <i class="bi bi-chevron-right" style="font-size: 0.7rem;"></i>
                </a>
            </div>

            <!-- Table Body with Fixed Responsive Layout -->
            <div class="card-body p-0 flex-grow-1">
                <div class="table-responsive" style="min-height: 280px;">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 140px;">Waktu</th>
                                <th style="width: 120px;" class="text-center">Tipe</th>
                                <th>Barang / Produk</th>
                                <th style="width: 110px;" class="text-end">Jumlah</th>
                                <th style="width: 130px;">Petugas</th>
                                <th style="width: 150px;">Dokumen / Ref</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMovements as $mov)
                                <tr>
                                    <!-- Waktu -->
                                    <td class="text-nowrap">
                                        <div class="fw-semibold text-dark" style="font-size: 0.88rem;">{{ $mov->created_at->format('d M Y') }}</div>
                                        <small class="text-muted" style="font-size: 0.75rem;">
                                            <i class="bi bi-clock me-1"></i>{{ $mov->created_at->format('H:i') }} WIB
                                        </small>
                                    </td>

                                    <!-- Tipe Mutasi -->
                                    <td class="text-center text-nowrap">
                                        @if($mov->type === 'IN')
                                            <span class="badge badge-stock-in px-2 py-1">
                                                <i class="bi bi-box-arrow-in-down"></i> MASUK
                                            </span>
                                        @else
                                            <span class="badge badge-stock-out px-2 py-1">
                                                <i class="bi bi-box-arrow-up"></i> KELUAR
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Produk & Kategori -->
                                    <td>
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('products.show', $mov->product_id) }}" class="fw-semibold text-decoration-none text-dark hover-primary" style="font-size: 0.88rem;">
                                                {{ $mov->product->name }}
                                            </a>
                                            <div class="d-flex align-items-center gap-1 mt-1">
                                                <code style="font-size: 0.75rem; padding: 1px 4px; background-color: #f1f5f9; border-radius: 4px;">{{ $mov->product->sku }}</code>
                                                <span class="text-muted" style="font-size: 0.72rem;">&bull; {{ $mov->product->category->name }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Kuantitas -->
                                    <td class="text-end text-nowrap">
                                        <span class="fw-bold fs-6 {{ $mov->type === 'IN' ? 'text-success' : 'text-danger' }}">
                                            {{ $mov->type === 'IN' ? '+' : '-' }}{{ number_format($mov->quantity) }}
                                        </span>
                                        <small class="text-muted" style="font-size: 0.75rem;">unit</small>
                                    </td>

                                    <!-- Petugas Input -->
                                    <td class="text-nowrap">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-slate-200 text-secondary d-flex align-items-center justify-content-center fw-semibold" style="width: 28px; height: 28px; font-size: 0.75rem; background-color: #f1f5f9;">
                                                {{ strtoupper(substr($mov->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="text-dark fw-medium" style="font-size: 0.84rem;">{{ $mov->user->name }}</div>
                                                <span class="badge {{ $mov->user->isAdmin() ? 'badge-role-admin' : 'badge-role-staff' }}" style="font-size: 0.65rem; padding: 1px 5px;">
                                                    {{ strtoupper($mov->user->role) }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Dokumen / Catatan -->
                                    <td>
                                        @if($mov->reference_number)
                                            <div class="fw-semibold text-dark text-truncate" style="font-size: 0.82rem; max-width: 140px;" title="{{ $mov->reference_number }}">
                                                <i class="bi bi-file-earmark-text text-muted me-1"></i>{{ $mov->reference_number }}
                                            </div>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                        @if($mov->notes)
                                            <div class="text-muted text-truncate" style="font-size: 0.74rem; max-width: 140px;" title="{{ $mov->notes }}">
                                                {{ $mov->notes }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                        <span>Belum ada riwayat transaksi mutasi stok.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer Card -->
            <div class="card-footer bg-light bg-opacity-50 py-3 px-4 d-flex justify-content-between align-items-center border-top">
                <small class="text-muted" style="font-size: 0.8rem;">
                    <i class="bi bi-info-circle me-1"></i> Seluruh mutasi divalidasi dengan row locking <code>lockForUpdate()</code>
                </small>
                <a href="{{ route('stock-movements.create') }}" class="btn btn-primary btn-sm px-3" style="font-size: 0.8rem; border-radius: 8px;">
                    <i class="bi bi-plus-lg me-1"></i> Buat Transaksi Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Side Widgets (Stok Rendah & Distribusi Kategori) -->
    <div class="col-xl-4 col-lg-5 d-flex flex-column gap-4">
        <!-- Widget 1: Peringatan Stok Rendah (Pertemuan 4 Slide 29) -->
        <div class="card shadow-sm border-0" style="border: 1px solid #fed7aa !important;">
            <div class="card-header py-3 px-4 d-flex justify-content-between align-items-center" style="background-color: #fffaf5; border-bottom: 1px solid #ffedd5;">
                <div class="d-flex align-items-center gap-2">
                    <span class="p-1 rounded bg-warning bg-opacity-25 text-warning d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                        <i class="bi bi-bell-fill fs-6 text-warning"></i>
                    </span>
                    <span class="fw-bold text-dark" style="font-size: 0.92rem;">Perhatian Stok Kritis</span>
                </div>
                <span class="badge bg-warning text-dark px-2 py-1" style="font-size: 0.74rem;">
                    {{ $lowStockCount }} Item
                </span>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($lowStockProducts as $low)
                        <li class="list-group-item py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
                            <div style="min-width: 0; flex: 1; padding-right: 12px;">
                                <a href="{{ route('products.show', $low->id) }}" class="fw-semibold text-dark text-decoration-none text-truncate d-block" style="font-size: 0.86rem;" title="{{ $low->name }}">
                                    {{ $low->name }}
                                </a>
                                <div class="d-flex align-items-center gap-1 mt-1 text-muted" style="font-size: 0.74rem;">
                                    <code>{{ $low->sku }}</code>
                                    <span>&bull; {{ $low->category->name }}</span>
                                </div>
                            </div>
                            <div class="text-end flex-shrink-0">
                                <span class="badge badge-low-stock px-2 py-1 d-block mb-1">
                                    {{ $low->stock }} / Min {{ $low->minimum_stock }}
                                </span>
                                <a href="{{ route('stock-movements.create', ['product_id' => $low->id, 'type' => 'IN']) }}" class="btn btn-outline-success btn-sm py-0 px-2" style="font-size: 0.72rem; border-radius: 6px;">
                                    <i class="bi bi-plus-lg"></i> Restock
                                </a>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-center py-4 text-muted" style="font-size: 0.86rem;">
                            <i class="bi bi-check2-circle text-success fs-3 d-block mb-1"></i>
                            Semua stok berada di atas batas minimum.
                        </li>
                    @endforelse
                </ul>
            </div>
            <div class="card-footer bg-light py-2 px-4 text-center border-top">
                <a href="{{ route('products.index', ['low_stock' => '1']) }}" class="text-decoration-none fw-semibold" style="font-size: 0.8rem; color: #b45309;">
                    Lihat Seluruh Produk Stok Rendah <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Widget 2: Distribusi Kategori (Pertemuan 4 Slide 14) -->
        <div class="card shadow-sm border-0">
            <div class="card-header py-3 px-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="p-1 rounded bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                        <i class="bi bi-pie-chart-fill fs-6 text-primary"></i>
                    </span>
                    <span class="fw-bold text-dark" style="font-size: 0.92rem;">Distribusi per Kategori</span>
                </div>
                <a href="{{ route('categories.index') }}" class="text-decoration-none text-muted small">Kelola</a>
            </div>
            <div class="card-body p-3 px-4">
                @php
                    $colors = ['#4f46e5', '#0ea5e9', '#10b981', '#f59e0b', '#8b5cf6'];
                @endphp
                @foreach($categorySummaries as $index => $cat)
                    @php
                        $percentage = $totalStockUnits > 0 ? round((($cat->products_sum_stock ?? 0) / $totalStockUnits) * 100) : 0;
                        $color = $colors[$index % count($colors)];
                    @endphp
                    <div class="mb-3 {{ $loop->last ? 'mb-0' : '' }}">
                        <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.82rem;">
                            <span class="fw-semibold text-dark text-truncate" style="max-width: 170px;">{{ $cat->name }}</span>
                            <span class="text-muted">
                                <strong>{{ $cat->products_count }}</strong> SKU &bull; <strong>{{ $cat->products_sum_stock ?? 0 }}</strong> unit ({{ $percentage }}%)
                            </span>
                        </div>
                        <div class="progress" style="height: 6px; border-radius: 4px; background-color: #f1f5f9;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%; background-color: {{ $color }}; border-radius: 4px;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Widget 3: Quick Action & Ringkasan Supplier -->
        <div class="card shadow-sm border-0" style="background-color: #f8fafc; border: 1px dashed #cbd5e1 !important;">
            <div class="card-body p-3 px-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: #e0f2fe; color: #0284c7;">
                        <i class="bi bi-truck fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark" style="font-size: 0.88rem;">Pemasok Terdaftar</div>
                        <small class="text-muted" style="font-size: 0.76rem;">{{ $totalSuppliers }} mitra penyuplai aktif</small>
                    </div>
                </div>
                <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary btn-sm" style="font-size: 0.78rem; border-radius: 8px;">
                    Lihat Mitra &rarr;
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
