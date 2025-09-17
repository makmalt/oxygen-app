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
                                    <div class="mb-2">
                                        <input type="text" id="search_botol" class="form-control"
                                            placeholder="Cari nomor/status botol...">
                                    </div>
                                    <div class="border rounded p-2" id="botol_list"
                                        style="max-height: 300px; overflow:auto;">
                                        @if (($botolCount ?? 0) <= 10)
                                            @foreach ($botols as $botol)
                                                @php($si = strtolower((string) $botol->status_isi))
                                                <div class="form-check botol-row" data-status="{{ $si }}"
                                                    data-nomor="{{ $botol->nomor_botol }}">
                                                    <input class="form-check-input botol-checkbox" type="checkbox"
                                                        name="botol_ids[]" value="{{ $botol->id }}"
                                                        id="b{{ $botol->id }}"
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
                                                            <span class="badge bg-secondary">Isi</span>
                                                        @endif
                                                    </label>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-muted small py-2">Scroll untuk memuat lebih banyak...</div>
                                        @endif
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
            const container = document.querySelector('#botol_list');
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

            // Search + infinite scroll (server-side when banyak data)
            (function() {
                const searchInput = document.getElementById('search_botol');
                const total = parseInt('{{ $botolCount ?? 0 }}', 10) || 0;
                const useServer = total > 10;
                let page = 1;
                let loading = false;
                let hasMore = true;

                function renderRows(items, append = true) {
                    const frag = document.createDocumentFragment();
                    items.forEach(item => {
                        const status = (item.status_isi || '').toLowerCase();
                        const wrap = document.createElement('div');
                        wrap.className = 'form-check botol-row';
                        wrap.setAttribute('data-status', status);
                        wrap.setAttribute('data-nomor', item.nomor_botol);
                        wrap.innerHTML = `
<input class="form-check-input botol-checkbox" type="checkbox" name="botol_ids[]" value="${item.id}" id="b${item.id}" ${status === 'masuk pabrik' ? 'disabled' : ''}>
<label class="form-check-label" for="b${item.id}" title="${status === 'masuk pabrik' ? 'Sedang di pabrik - tidak dapat dipilih' : ''}">
    ${item.nomor_botol}
    ${status === 'terisi' ? '<span class="badge bg-success">Terisi</span>' : status === 'kosong' ? '<span class="badge bg-warning">Kosong</span>' : status === 'masuk pabrik' ? '<span class="badge bg-info">Masuk Pabrik</span>' : '<span class="badge bg-secondary">Isi</span>'}
</label>`;
                        frag.appendChild(wrap);
                    });
                    if (!append) container.innerHTML = '';
                    container.appendChild(frag);
                }

                async function fetchServer(reset = false) {
                    if (loading || !hasMore && !reset) return;
                    loading = true;
                    const q = encodeURIComponent((searchInput?.value || '').trim());
                    if (reset) {
                        page = 1;
                        hasMore = true;
                    }
                    const url =
                        `{{ route('transaksi_isi_botol.botols') }}?page=${page}&per_page=20${q ? `&q=${q}` : ''}`;
                    try {
                        const res = await fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const json = await res.json();
                        hasMore = !!json.has_more;
                        if (reset) renderRows(json.data, false);
                        else renderRows(json.data, true);
                        page += 1;
                    } catch (_) {
                        // ignore
                    } finally {
                        loading = false;
                    }
                }

                if (useServer) {
                    // initial load
                    fetchServer(true);
                    // scroll handler
                    container?.addEventListener('scroll', function() {
                        if (!hasMore || loading) return;
                        const nearBottom = container.scrollTop + container.clientHeight >= container
                            .scrollHeight - 24;
                        if (nearBottom) fetchServer();
                    });
                    // search handler
                    searchInput?.addEventListener('input', function() {
                        fetchServer(true);
                    });
                } else {
                    // client-side filter for small dataset
                    if (searchInput) {
                        function filterRows() {
                            const q = (searchInput.value || '').toLowerCase().trim();
                            const rows = Array.from(container.querySelectorAll('.botol-row'));
                            if (!q) {
                                rows.forEach(r => r.style.display = '');
                                return;
                            }
                            rows.forEach(r => {
                                const nomor = (r.getAttribute('data-nomor') || '').toLowerCase();
                                const status = (r.getAttribute('data-status') || '').toLowerCase();
                                const text = (r.textContent || '').toLowerCase();
                                const match = nomor.includes(q) || status.includes(q) || text.includes(q);
                                r.style.display = match ? '' : 'none';
                            });
                        }
                        searchInput.addEventListener('input', filterRows);
                    }
                }
            })();
        })();
    </script>
@endpush
