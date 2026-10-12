@extends('layouts.app')

@section('title', 'Transaksi Mutasi Stok')
@section('page_title', 'Catat Mutasi Stok Barang')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('stock-movements.index') }}" class="text-decoration-none">Mutasi Stok</a></li>
    <li class="breadcrumb-item active" aria-current="page">Transaksi Baru</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-arrow-left-right me-1 text-primary"></i> Formulir Transaksi Barang Masuk & Keluar</span>
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                    <i class="bi bi-shield-check"></i> Dilindungi Database Transaction
                </span>
            </div>
            <div class="card-body p-4">
                <!-- Info Alert tentang Mekanisme Keamanan Transaksi (P4 Slide 25-27) -->
                <div class="alert alert-info py-2 px-3 d-flex align-items-center gap-2 mb-4" style="font-size: 0.85rem;">
                    <i class="bi bi-info-circle-fill fs-5 text-info"></i>
                    <div>
                        Transaksi ini dijamin oleh <strong>DB::transaction</strong> dan <strong>row-level locking (FOR UPDATE)</strong>. Untuk barang keluar, sistem akan memvalidasi ketersediaan stok fisik di database agar stok tidak pernah bernilai negatif.
                    </div>
                </div>

                <form action="{{ route('stock-movements.store') }}" method="POST" id="transactionForm">
                    @csrf

                    <!-- 1. Pilihan Tipe Transaksi -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Tipe Mutasi Persediaan <span class="text-danger">*</span></label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="card h-100 p-3 border cursor-pointer type-card" id="cardIn" style="cursor: pointer;">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="type"
                                               id="typeIn"
                                               value="IN"
                                               {{ old('type', $defaultType) === 'IN' ? 'checked' : '' }}
                                               onchange="updateTypeUI()">
                                        <label class="form-check-label fw-bold text-success" for="typeIn">
                                            <i class="bi bi-box-arrow-in-down fs-5 me-1"></i> Barang Masuk (Stock In)
                                        </label>
                                    </div>
                                    <small class="text-muted ps-4 d-block mt-1">
                                        Penerimaan barang dari supplier, pengadaan baru, atau retur pelanggan. Menambah stok.
                                    </small>
                                </label>
                            </div>

                            <div class="col-md-6">
                                <label class="card h-100 p-3 border cursor-pointer type-card" id="cardOut" style="cursor: pointer;">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="type"
                                               id="typeOut"
                                               value="OUT"
                                               {{ old('type', $defaultType) === 'OUT' ? 'checked' : '' }}
                                               onchange="updateTypeUI()">
                                        <label class="form-check-label fw-bold text-danger" for="typeOut">
                                            <i class="bi bi-box-arrow-up fs-5 me-1"></i> Barang Keluar (Stock Out)
                                        </label>
                                    </div>
                                    <small class="text-muted ps-4 d-block mt-1">
                                        Pengeluaran untuk penjualan, distribusi divisi, atau pemakaian operasional. Mengurangi stok.
                                    </small>
                                </label>
                            </div>
                        </div>
                        @error('type')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- 2. Pilihan Produk -->
                    <div class="mb-3">
                        <label for="product_id" class="form-label fw-bold">Pilih Produk <span class="text-danger">*</span></label>
                        <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" required onchange="handleProductChange()">
                            <option value="">-- Pilih Barang dari Katalog --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}"
                                        data-stock="{{ $p->stock }}"
                                        data-sku="{{ $p->sku }}"
                                        data-name="{{ $p->name }}"
                                        {{ (old('product_id', $selectedProductId) == $p->id) ? 'selected' : '' }}>
                                    [{{ $p->sku }}] {{ $p->name }} &mdash; (Tersedia: {{ $p->stock }} unit)
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <!-- Live Stock Indicator Box -->
                        <div id="stockInfoBox" class="mt-2 p-2 bg-light rounded border d-none" style="font-size: 0.85rem;">
                            <span class="text-muted">Stok fisik saat ini:</span>
                            <span class="fw-bold fs-6 ms-1" id="currentStockDisplay">0</span> unit
                            <span id="lowStockWarning" class="text-danger ms-2 fw-semibold d-none">
                                <i class="bi bi-exclamation-triangle"></i> Perhatian: Stok terbatas!
                            </span>
                        </div>
                    </div>

                    <!-- 3. Jumlah Kuantitas -->
                    <div class="mb-3">
                        <label for="quantity" class="form-label fw-bold">Jumlah Unit (Quantity) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number"
                                   name="quantity"
                                   id="quantity"
                                   class="form-control @error('quantity') is-invalid @enderror"
                                   value="{{ old('quantity', 1) }}"
                                   min="1"
                                   required
                                   oninput="checkStockLimit()">
                            <span class="input-group-text">Unit</span>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div id="quantityWarning" class="text-danger small mt-1 d-none fw-semibold">
                            <i class="bi bi-exclamation-octagon-fill"></i> Peringatan: Jumlah permintaan melebihi stok yang tersedia saat ini!
                        </div>
                    </div>

                    <!-- 4. Nomor Referensi / Surat Jalan -->
                    <div class="mb-3">
                        <label for="reference_number" class="form-label fw-bold">Nomor Dokumen Referensi</label>
                        <input type="text"
                               name="reference_number"
                               id="reference_number"
                               class="form-control @error('reference_number') is-invalid @enderror"
                               value="{{ old('reference_number') }}"
                               placeholder="Contoh: PO-2026-009, DO-OUT-021, No. Resi, dll.">
                        @error('reference_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Opsional. Mempermudah pencocokan dengan dokumen fisik atau nota gudang.</div>
                    </div>

                    <!-- 5. Keterangan / Catatan -->
                    <div class="mb-4">
                        <label for="notes" class="form-label fw-bold">Catatan / Keperluan</label>
                        <textarea name="notes"
                                  id="notes"
                                  rows="3"
                                  class="form-control @error('notes') is-invalid @enderror"
                                  placeholder="Contoh: Pengadaan batch 2, Pengiriman ke divisi marketing, barang rusak/retur...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                            <i class="bi bi-check2-circle me-1"></i> Proses Transaksi Mutasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateTypeUI() {
    const isOut = document.getElementById('typeOut').checked;
    const cardIn = document.getElementById('cardIn');
    const cardOut = document.getElementById('cardOut');

    if (isOut) {
        cardOut.classList.add('border-danger', 'bg-danger', 'bg-opacity-10');
        cardIn.classList.remove('border-success', 'bg-success', 'bg-opacity-10');
    } else {
        cardIn.classList.add('border-success', 'bg-success', 'bg-opacity-10');
        cardOut.classList.remove('border-danger', 'bg-danger', 'bg-opacity-10');
    }
    checkStockLimit();
}

function handleProductChange() {
    const select = document.getElementById('product_id');
    const selected = select.options[select.selectedIndex];
    const infoBox = document.getElementById('stockInfoBox');
    const display = document.getElementById('currentStockDisplay');
    const lowWarning = document.getElementById('lowStockWarning');

    if (selected && selected.value) {
        const stock = parseInt(selected.getAttribute('data-stock') || '0');
        display.textContent = stock;
        infoBox.classList.remove('d-none');
        if (stock <= 5) {
            lowWarning.classList.remove('d-none');
        } else {
            lowWarning.classList.add('d-none');
        }
    } else {
        infoBox.classList.add('d-none');
    }
    checkStockLimit();
}

function checkStockLimit() {
    const isOut = document.getElementById('typeOut').checked;
    const select = document.getElementById('product_id');
    const qtyInput = document.getElementById('quantity');
    const warning = document.getElementById('quantityWarning');
    const submitBtn = document.getElementById('submitBtn');

    if (isOut && select.selectedIndex > 0) {
        const currentStock = parseInt(select.options[select.selectedIndex].getAttribute('data-stock') || '0');
        const qty = parseInt(qtyInput.value || '0');

        if (qty > currentStock) {
            warning.classList.remove('d-none');
            qtyInput.classList.add('is-invalid');
        } else {
            warning.classList.add('d-none');
            qtyInput.classList.remove('is-invalid');
        }
    } else {
        warning.classList.add('d-none');
        qtyInput.classList.remove('is-invalid');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateTypeUI();
    handleProductChange();
});
</script>
@endpush
