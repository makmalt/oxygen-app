@extends('layouts.app')

@section('title', 'Pengiriman Botol ke Pabrik')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                    <li class="breadcrumb-item"><a href="#">Isi Botol</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Kirim ke Pabrik</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Kirim Botol ke Pabrik</h5>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('transaksi_isi_botol.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_isi" class="form-label">Tanggal Kirim<span
                                            style="color:red">*</span></label>
                                    <input type="date" id="tanggal_isi" name="tanggal_isi"
                                        class="form-control @error('tanggal_isi') is-invalid @enderror" required>
                                    @error('tanggal_isi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="supplier_id" class="form-label">Supplier/Pabrik</label>
                                    <select id="supplier_id" name="supplier_id"
                                        class="form-control @error('supplier_id') is-invalid @enderror">
                                        <option value="">Pilih Supplier (opsional)</option>
                                        @isset($suppliers)
                                            @foreach ($suppliers as $s)
                                                <option value="{{ $s->id }}">{{ $s->nama_supplier }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    @error('supplier_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Pilih Botol yang Dikirim<span
                                            style="color:red">*</span></label>
                                    <div class="border rounded p-2" style="max-height: 300px; overflow:auto;">
                                        @foreach ($botols as $botol)
                                            @php($si = strtolower((string) $botol->status_isi))
                                            <div class="form-check botol-row" data-status="{{ $si }}"
                                                data-nomor="{{ $botol->nomor_botol }}">
                                                <input class="form-check-input botol-checkbox" type="checkbox"
                                                    name="botol_ids[]" value="{{ $botol->id }}" id="b{{ $botol->id }}"
                                                    {{ $si === 'masuk pabrik' ? 'disabled' : '' }}>
                                                <label class="form-check-label" for="b{{ $botol->id }}"
                                                    title="{{ $si === 'masuk pabrik' ? 'Sedang di pabrik - tidak dapat dipilih' : '' }}">
                                                    {{ $botol->nomor_botol }}
                                                    @if ($si === 'terisi')
                                                        <span class="badge bg-success">Terisi</span>
                                                    @elseif($si === 'kosong')
                                                        <span class="badge bg-warning">Kosong</span>
                                                    @elseif($si === 'masuk pabrik')
                                                        <span class="badge bg-info">Masuk Pabrik</span>
                                                    @else
                                                        <span class="badge bg-secondary">-</span>
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('botol_ids')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Buat Pengiriman</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const container = document.querySelector('.border.rounded.p-2');
            const toastId = 'toastMasukPabrik';

            function ensureToast() {
                let toastEl = document.getElementById(toastId);
                if (!toastEl) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'toast-container position-fixed top-0 end-0 p-3';
                    // Pastikan tidak tertutup navbar
                    wrapper.style.zIndex = '2000';
                    wrapper.style.top = '72px'; // offset di bawah navbar
                    wrapper.innerHTML = `
                        <div id="${toastId}" class="toast align-items-center text-bg-warning border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
                            <div class="d-flex">
                                <div class="toast-body">Botol sedang di pabrik dan tidak bisa dipilih.</div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>`;
                    document.body.appendChild(wrapper);
                    toastEl = document.getElementById(toastId);
                }
                return bootstrap.Toast.getOrCreateInstance(toastEl);
            }

            if (container) {
                container.addEventListener('click', function(e) {
                    const row = e.target.closest('.botol-row');
                    if (!row) return;
                    const status = (row.getAttribute('data-status') || '').toLowerCase();
                    if (status === 'masuk pabrik') {
                        e.preventDefault();
                        e.stopPropagation();
                        const cb = row.querySelector('.botol-checkbox');
                        if (cb) cb.checked = false;
                        ensureToast().show();
                    }
                }, true);
            }
        })();
    </script>
@endpush
