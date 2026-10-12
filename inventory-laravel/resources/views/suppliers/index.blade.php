@extends('layouts.app')

@section('title', 'Data Supplier')
@section('page_title', 'Master Data Supplier')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Supplier</li>
@endsection

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body bg-light rounded-top border-bottom">
        <div class="row g-2 align-items-center justify-content-between">
            <div class="col-md-5">
                <form action="{{ route('suppliers.index') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Cari nama, email, telepon supplier..."
                               value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Cari</button>
                    @if(request('search'))
                        <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                    @endif
                </form>
            </div>

            @if(Auth::user() && Auth::user()->isAdmin())
            <div class="col-md-auto">
                <a href="{{ route('suppliers.create') }}" class="btn btn-sm btn-success">
                    <i class="bi bi-plus-lg"></i> Tambah Supplier
                </a>
            </div>
            @endif
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Nama Supplier</th>
                        <th>Kontak & Email</th>
                        <th>Alamat</th>
                        <th class="text-center">Produk Disuplai</th>
                        <th class="text-end" style="width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                        <tr>
                            <td class="text-muted">{{ $loop->iteration + ($suppliers->currentPage() - 1) * $suppliers->perPage() }}</td>
                            <td>
                                <a href="{{ route('suppliers.show', $supplier->id) }}" class="fw-semibold text-decoration-none text-dark">
                                    {{ $supplier->name }}
                                </a>
                            </td>
                            <td>
                                <div><i class="bi bi-telephone text-muted me-1"></i> {{ $supplier->phone ?: '-' }}</div>
                                <div class="text-muted small"><i class="bi bi-envelope text-muted me-1"></i> {{ $supplier->email ?: '-' }}</div>
                            </td>
                            <td class="text-muted" style="font-size: 0.88rem;">{{ $supplier->address ?: '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1">
                                    {{ $supplier->products_count }} Produk
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-outline-secondary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @if(Auth::user() && Auth::user()->isAdmin())
                                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus supplier ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada supplier yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($suppliers->hasPages())
    <div class="card-footer bg-white py-3 d-flex justify-content-between align-items-center">
        <div class="small text-muted">
            Menampilkan {{ $suppliers->firstItem() }} sampai {{ $suppliers->lastItem() }} dari {{ $suppliers->total() }} data
        </div>
        <div>
            {{ $suppliers->links('pagination::bootstrap-5') }}
        </div>
    </div>
    @endif
</div>
@endsection
