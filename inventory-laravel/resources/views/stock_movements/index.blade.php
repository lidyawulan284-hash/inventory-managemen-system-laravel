@extends('layouts.app')

@section('title', 'Riwayat Mutasi Stok')
@section('page_title', 'Riwayat Mutasi Stok (Stock Movements)')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Mutasi Stok</li>
@endsection

@section('content')
<div class="card shadow-sm mb-4">
    <!-- Filter and Search Toolbar -->
    <div class="card-body bg-light rounded-top border-bottom">
        <form action="{{ route('stock-movements.index') }}" method="GET" class="row g-2 align-items-center">
            <!-- Search Text -->
            <div class="col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Cari referensi, catatan, produk..."
                           value="{{ request('search') }}">
                </div>
            </div>

            <!-- Filter Type -->
            <div class="col-md-3">
                <select name="type" class="form-select form-select-sm">
                    <option value="">-- Semua Tipe Transaksi --</option>
                    <option value="IN" {{ request('type') === 'IN' ? 'selected' : '' }}>Barang Masuk (IN)</option>
                    <option value="OUT" {{ request('type') === 'OUT' ? 'selected' : '' }}>Barang Keluar (OUT)</option>
                </select>
            </div>

            <!-- Filter Product -->
            <div class="col-md-3">
                <select name="product_id" class="form-select form-select-sm">
                    <option value="">-- Semua Produk --</option>
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}" {{ request('product_id') == $prod->id ? 'selected' : '' }}>
                            {{ $prod->name }} ({{ $prod->sku }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Buttons -->
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary flex-grow-1">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <a href="{{ route('stock-movements.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
                <a href="{{ route('stock-movements.create') }}" class="btn btn-sm btn-success text-nowrap">
                    <i class="bi bi-plus-lg"></i> Catat Mutasi
                </a>
            </div>
        </form>
    </div>

    <!-- Movement Table -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 130px;">Waktu</th>
                        <th style="width: 120px;">Tipe</th>
                        <th>Produk</th>
                        <th class="text-center" style="width: 130px;">Jumlah</th>
                        <th>Petugas Input</th>
                        <th>No. Referensi</th>
                        <th>Keterangan / Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $m)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">{{ $m->created_at->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $m->created_at->format('H:i') }} WIB</small>
                            </td>
                            <td>
                                @if($m->type === 'IN')
                                    <span class="badge badge-stock-in px-2 py-1">
                                        <i class="bi bi-box-arrow-in-down"></i> MASUK (IN)
                                    </span>
                                @else
                                    <span class="badge badge-stock-out px-2 py-1">
                                        <i class="bi bi-box-arrow-up"></i> KELUAR (OUT)
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('products.show', $m->product_id) }}" class="fw-semibold text-decoration-none text-primary">
                                    {{ $m->product->name }}
                                </a>
                                <div class="text-muted small">
                                    <code>{{ $m->product->sku }}</code> &bull; {{ $m->product->category->name }}
                                </div>
                            </td>
                            <td class="text-center fw-bold fs-6 {{ $m->type === 'IN' ? 'text-success' : 'text-danger' }}">
                                {{ $m->type === 'IN' ? '+' : '-' }}{{ number_format($m->quantity) }} unit
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $m->user->name }}</div>
                                <span class="badge {{ $m->user->isAdmin() ? 'badge-role-admin' : 'badge-role-staff' }}" style="font-size: 0.68rem;">
                                    {{ strtoupper($m->user->role) }}
                                </span>
                            </td>
                            <td>
                                @if($m->reference_number)
                                    <code>{{ $m->reference_number }}</code>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-secondary small">{{ $m->notes ?: '-' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-arrow-left-right fs-1 d-block mb-2"></i>
                                Tidak ada data transaksi mutasi stok yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($movements->hasPages())
    <div class="card-footer bg-white py-3 d-flex justify-content-between align-items-center">
        <div class="small text-muted">
            Menampilkan {{ $movements->firstItem() }} sampai {{ $movements->lastItem() }} dari {{ $movements->total() }} transaksi
        </div>
        <div>
            {{ $movements->links('pagination::bootstrap-5') }}
        </div>
    </div>
    @endif
</div>
@endsection
