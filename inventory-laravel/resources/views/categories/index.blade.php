@extends('layouts.app')

@section('title', 'Kategori Produk')
@section('page_title', 'Master Data Kategori')

@section('breadcrumb')
    <span class="mx-2 text-muted">/</span>
    <span class="text-dark fw-medium">Kategori Produk</span>
@endsection

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body bg-light rounded-top border-bottom">
        <div class="row g-2 align-items-center justify-content-between">
            <div class="col-md-5">
                <form action="{{ route('categories.index') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Cari kategori..."
                               value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Cari</button>
                    @if(request('search'))
                        <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                    @endif
                </form>
            </div>

            @if(Auth::user() && Auth::user()->isAdmin())
            <div class="col-md-auto">
                <a href="{{ route('categories.create') }}" class="btn btn-sm btn-success">
                    <i class="bi bi-plus-lg"></i> Tambah Kategori
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
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Jumlah Produk</th>
                        <th class="text-end" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td class="text-muted">{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                            <td class="fw-semibold text-dark">{{ $category->name }}</td>
                            <td class="text-muted" style="font-size: 0.88rem;">{{ $category->description ?: '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('products.index', ['category_id' => $category->id]) }}" class="badge bg-primary bg-opacity-10 text-primary text-decoration-none border border-primary border-opacity-25 px-2 py-1">
                                    {{ $category->products_count }} Produk
                                </a>
                            </td>
                            <td class="text-end">
                                @if(Auth::user() && Auth::user()->isAdmin())
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted small">Hanya Baca</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada kategori yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($categories->hasPages())
    <div class="card-footer bg-white py-3 d-flex justify-content-between align-items-center">
        <div class="small text-muted">
            Menampilkan {{ $categories->firstItem() }} sampai {{ $categories->lastItem() }} dari {{ $categories->total() }} data
        </div>
        <div>
            {{ $categories->links('pagination::bootstrap-5') }}
        </div>
    </div>
    @endif
</div>
@endsection
