@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('page_title', 'Manajemen Pengguna Sistem')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Pengguna</li>
@endsection

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body bg-light rounded-top border-bottom">
        <div class="row g-2 align-items-center justify-content-between">
            <div class="col-md-6">
                <form action="{{ route('users.index') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Cari nama, username, email..."
                               value="{{ request('search') }}">
                    </div>

                    <select name="role" class="form-select form-select-sm" style="max-width: 150px;">
                        <option value="">-- Semua Role --</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="staff" {{ request('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>

                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    @if(request('search') || request('role'))
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                    @endif
                </form>
            </div>

            <div class="col-md-auto">
                <a href="{{ route('users.create') }}" class="btn btn-sm btn-success">
                    <i class="bi bi-person-plus-fill"></i> Tambah Pengguna
                </a>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Nama Pengguna</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th class="text-center">Role / Hak Akses</th>
                        <th class="text-center">Riwayat Transaksi</th>
                        <th class="text-end" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="text-muted">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                            <td class="fw-semibold text-dark">
                                {{ $user->name }}
                                @if($user->id === Auth::id())
                                    <span class="badge bg-light text-primary border ms-1" style="font-size: 0.7rem;">Anda</span>
                                @endif
                            </td>
                            <td><code>{{ $user->username ?? '-' }}</code></td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">
                                @if($user->isAdmin())
                                    <span class="badge badge-role-admin px-2 py-1"><i class="bi bi-shield-lock-fill"></i> Admin</span>
                                @else
                                    <span class="badge badge-role-staff px-2 py-1"><i class="bi bi-person-badge-fill"></i> Staff</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border">
                                    {{ $user->stock_movements_count }} Transaksi
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    @if($user->id !== Auth::id())
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna ini?');">
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
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada pengguna terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($users->hasPages())
    <div class="card-footer bg-white py-3 d-flex justify-content-between align-items-center">
        <div class="small text-muted">
            Menampilkan {{ $users->firstItem() }} sampai {{ $users->lastItem() }} dari {{ $users->total() }} pengguna
        </div>
        <div>
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
    @endif
</div>
@endsection
