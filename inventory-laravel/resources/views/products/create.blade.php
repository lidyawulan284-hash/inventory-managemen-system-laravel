@extends('layouts.app')

@section('title', 'Tambah Produk Baru')
@section('page_title', 'Tambah Produk Baru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Produk</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <i class="bi bi-plus-circle me-1 text-primary"></i> Formulir Tambah Master Produk
            </div>
            <div class="card-body p-4">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <!-- SKU -->
                        <div class="col-md-4">
                            <label for="sku" class="form-label fw-semibold">Kode SKU <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="sku"
                                   id="sku"
                                   class="form-control @error('sku') is-invalid @enderror"
                                   value="{{ old('sku') }}"
                                   placeholder="Contoh: SKU-KB-001"
                                   required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">SKU harus bersifat unik di seluruh katalog.</div>
                        </div>

                        <!-- Nama Produk -->
                        <div class="col-md-8">
                            <label for="name" class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="Nama lengkap barang/produk"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div class="col-md-6">
                            <label for="category_id" class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Harga -->
                        <div class="col-md-6">
                            <label for="price" class="form-label fw-semibold">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number"
                                       name="price"
                                       id="price"
                                       class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price', 0) }}"
                                       min="0"
                                       step="100"
                                       required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Stok Awal -->
                        <div class="col-md-6">
                            <label for="stock" class="form-label fw-semibold">Stok Awal <span class="text-danger">*</span></label>
                            <input type="number"
                                   name="stock"
                                   id="stock"
                                   class="form-control @error('stock') is-invalid @enderror"
                                   value="{{ old('stock', 0) }}"
                                   min="0"
                                   required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Nilai stok awal saat pencatatan produk baru.</div>
                        </div>

                        <!-- Minimum Stok -->
                        <div class="col-md-6">
                            <label for="minimum_stock" class="form-label fw-semibold">Ambang Batas Minimum Stok <span class="text-danger">*</span></label>
                            <input type="number"
                                   name="minimum_stock"
                                   id="minimum_stock"
                                   class="form-control @error('minimum_stock') is-invalid @enderror"
                                   value="{{ old('minimum_stock', 5) }}"
                                   min="0"
                                   required>
                            @error('minimum_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Peringatan stok rendah akan aktif jika stok &le; angka ini.</div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold">Deskripsi / Spesifikasi</label>
                            <textarea name="description"
                                      id="description"
                                      rows="3"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Penjelasan detail fisik atau fungsi produk...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Suppliers (Many-to-Many) -->
                        <div class="col-12">
                            <label class="form-label fw-semibold d-block">Pemasok / Supplier Terkait (Many-to-Many)</label>
                            <div class="row g-2 p-3 bg-light rounded-3 border">
                                @forelse($suppliers as $supplier)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   name="suppliers[]"
                                                   value="{{ $supplier->id }}"
                                                   id="sup_{{ $supplier->id }}"
                                                   {{ (is_array(old('suppliers')) && in_array($supplier->id, old('suppliers'))) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="sup_{{ $supplier->id }}">
                                                <strong>{{ $supplier->name }}</strong>
                                                <small class="text-muted d-block">{{ $supplier->email ?? 'No email' }}</small>
                                            </label>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-muted small">Belum ada data supplier terdaftar.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
