@extends('layouts.app')

@section('title', 'POS Karya Hutama Oxygen - Tambah Botol')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Botol</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('data_botol.index') }}">Daftar Botol</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah Botol</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Tambah Botol</h5>
            </div>
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <form action="{{ route('data_botol.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nomor_botol" class="form-label">Nomor Botol<span
                                            style="color: red;">*</span></label>
                                    <input type="text" class="form-control @error('nomor_botol') is-invalid @enderror"
                                        id="nomor_botol" name="nomor_botol" value="{{ old('nomor_botol') }}" required>
                                    @error('nomor_botol')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="jenis_botol" class="form-label">Jenis Botol<span
                                            style="color: red;">*</span></label>
                                    <div class="d-flex gap-2">
                                        <select class="form-control @error('jenis_botol') is-invalid @enderror"
                                            id="jenis_botol" name="jenis_botol" required>
                                            <option value="">Pilih Jenis Botol</option>
                                            @isset($jenis)
                                                @foreach ($jenis as $item)
                                                    <option value="{{ $item->nama_jenis }}"
                                                        {{ old('jenis_botol') == $item->nama_jenis ? 'selected' : '' }}>
                                                        {{ $item->nama_jenis }}
                                                    </option>
                                                @endforeach
                                            @endisset
                                        </select>
                                        <button type="button" class="btn btn-outline-secondary" title="Tambah Jenis"
                                            data-bs-toggle="modal" data-bs-target="#modalJenis">+
                                        </button>
                                    </div>
                                    @error('jenis_botol')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <a href="{{ route('data_botol.index') }}" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Jenis Botol -->
    <div class="modal fade" id="modalJenis" tabindex="-1" aria-labelledby="modalJenisLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalJenisLabel">Tambah Jenis Botol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_jenis" class="form-label">Nama Jenis<span style="color: red;">*</span></label>
                        <input type="text" id="nama_jenis" class="form-control" placeholder="cth: Oxygen 6">
                        <div class="invalid-feedback" id="nama_jenis_error" style="display:none;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="btnSimpanJenis">Simpan Jenis</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const jenisSelect = document.getElementById('jenis_botol');
            const inputNamaJenis = document.getElementById('nama_jenis');
            const errorNamaJenis = document.getElementById('nama_jenis_error');
            const btnSimpanJenis = document.getElementById('btnSimpanJenis');

            function refreshJenisOptions(selectedNama) {
                fetch("{{ route('jenis_botol.index') }}", {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        // rebuild options
                        const current = jenisSelect.value;
                        jenisSelect.innerHTML = '';
                        const optDefault = document.createElement('option');
                        optDefault.value = '';
                        optDefault.textContent = 'Pilih Jenis Botol';
                        jenisSelect.appendChild(optDefault);
                        data.forEach(item => {
                            const opt = document.createElement('option');
                            opt.value = item.nama_jenis;
                            opt.textContent = item.nama_jenis;
                            jenisSelect.appendChild(opt);
                        });
                        const toSelect = selectedNama || current;
                        if (toSelect) {
                            jenisSelect.value = toSelect;
                        }
                    })
                    .catch(() => {});
            }

            btnSimpanJenis?.addEventListener('click', function() {
                const nama = (inputNamaJenis?.value || '').trim();
                errorNamaJenis.style.display = 'none';
                errorNamaJenis.textContent = '';
                inputNamaJenis.classList.remove('is-invalid');
                if (!nama) {
                    inputNamaJenis.classList.add('is-invalid');
                    errorNamaJenis.textContent = 'Nama jenis wajib diisi';
                    errorNamaJenis.style.display = 'block';
                    return;
                }

                fetch("{{ route('jenis_botol.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            nama_jenis: nama
                        })
                    })
                    .then(async res => {
                        if (res.ok) return res.json();
                        const payload = await res.json().catch(() => ({}));
                        throw payload;
                    })
                    .then(data => {
                        // close modal and refresh select
                        const modalEl = document.getElementById('modalJenis');
                        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                        modal.hide();
                        inputNamaJenis.value = '';
                        refreshJenisOptions(data.nama_jenis);
                    })
                    .catch(err => {
                        const msg = err?.message || (err?.errors?.nama_jenis?.[0]) ||
                            'Gagal menyimpan jenis';
                        inputNamaJenis.classList.add('is-invalid');
                        errorNamaJenis.textContent = msg;
                        errorNamaJenis.style.display = 'block';
                    });
            });

            // initial populate if server didn't pass options
            if (!jenisSelect.querySelector('option[value]:not([value=""])')) {
                refreshJenisOptions();
            }
        })();
    </script>
@endsection
