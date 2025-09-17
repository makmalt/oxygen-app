@extends('layouts.app')

@section('title', 'POS Karya Hutama Oxygen - Form Pinjaman')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Pinjaman</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Form Pinjaman</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('data_pinjaman.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="nomor_botol_sebelumnya" class="form-label">Nomor Botol Sebelumnya</label>
                                    <select class="form-select select2" name="nomor_botol_sebelumnya"
                                        id="nomor_botol_sebelumnya" data-placeholder="Pilih Nomor Botol Sebelumnya">
                                        <option value=""></option>
                                        @foreach ($data_botols as $data_botol)
                                            <option value="{{ $data_botol->id }}">{{ $data_botol->nomor_botol }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3" id="pelanggan_sebelumnya_container" style="display: none;">
                                    <label class="form-label">Pelanggan yang Pernah Meminjam Botol Ini:</label>
                                    <div id="daftar_pelanggan_sebelumnya" class="border rounded p-3 bg-light">
                                        <!-- Daftar pelanggan akan dimuat di sini -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="tanggal_pengembalian" class="form-label">Tanggal Pengembalian</label>
                                    <input type="date" class="form-control" name="tanggal_pengembalian"
                                        id="tanggal_pengembalian">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="nomor_botol" class="form-label">Nomor Botol</label>
                                    <select class="form-select select2" name="nomor_botol" id="nomor_botol"
                                        data-placeholder="Pilih Nomor Botol">
                                        <option value=""></option>
                                        @foreach ($data_botols as $data_botol)
                                            <option value="{{ $data_botol->id }}">{{ $data_botol->nomor_botol }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
                                    <select class="form-select select2" name="nama_pelanggan" id="nama_pelanggan"
                                        data-placeholder="Pilih Nama Pelanggan">
                                        <option value=""></option>
                                        @foreach ($data_pelanggans as $data_pelanggan)
                                            <option value="{{ $data_pelanggan->id }}"
                                                data-alamat="{{ $data_pelanggan->alamat }}"
                                                data-nohp="{{ $data_pelanggan->no_hp }}">{{ $data_pelanggan->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12" id="pelanggan_detail_container" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label">Detail Pelanggan</label>
                                    <div class="border rounded p-3 bg-light" id="pelanggan_detail">
                                        <!-- Detail pelanggan akan tampil di sini -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="tanggal_pinjaman" class="form-label">Tanggal Pinjaman</label>
                                    <input type="date" class="form-control" name="tanggal_pinjaman"
                                        id="tanggal_pinjaman">
                                </div>
                                <div class="mb-3">
                                    <label for="penanggung_jawab_id" class="form-label">Penanggung Jawab</label>
                                    <div class="d-flex gap-2">
                                        <select class="form-select select2" name="penanggung_jawab_id"
                                            id="penanggung_jawab_id" data-placeholder="Penanggung Jawab">
                                            <option value=""></option>
                                            @foreach ($penanggung_jawabs as $penanggung_jawab)
                                                <option value="{{ $penanggung_jawab->id }}">
                                                    {{ $penanggung_jawab->nama }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-outline-secondary"
                                            title="Tambah Penanggung Jawab" data-bs-toggle="modal"
                                            data-bs-target="#modalPenanggungJawab">+
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Penanggung Jawab -->
    <div class="modal fade" id="modalPenanggungJawab" tabindex="-1" aria-labelledby="modalPenanggungJawabLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPenanggungJawabLabel">Tambah Penanggung Jawab</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Penanggung Jawab<span
                                style="color: red;">*</span></label>
                        <input type="text" id="nama" class="form-control" placeholder="cth: Budi">
                        <div class="invalid-feedback" id="nama_error" style="display:none;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="btnSimpanPenanggungJawab">Simpan Penanggung
                        Jawab</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
                allowClear: true,
                placeholder: function() {
                    return $(this).data('placeholder') || '';
                }
            });

            // Detail pelanggan ketika select berubah
            $('#nama_pelanggan').on('change', function() {
                var selected = $(this).find(':selected');
                var container = $('#pelanggan_detail_container');
                var detailBox = $('#pelanggan_detail');

                if (selected.val()) {
                    var alamat = selected.data('alamat') || 'Alamat tidak tersedia';
                    var nohp = selected.data('nohp') || 'No HP tidak tersedia';
                    var nama = selected.text();

                    var html = '' +
                        '<div class="d-flex flex-column">' +
                        '<strong>' + nama + '</strong>' +
                        '<span class="small text-muted">' + alamat + '</span>' +
                        '<span class="small">' + nohp + '</span>' +
                        '</div>';
                    detailBox.html(html);
                    container.show();
                } else {
                    container.hide();
                }
            });

            // Event handler untuk nomor botol sebelumnya
            $('#nomor_botol_sebelumnya').on('change', function() {
                var botolId = $(this).val();
                var container = $('#pelanggan_sebelumnya_container');
                var daftarContainer = $('#daftar_pelanggan_sebelumnya');

                if (botolId) {
                    // Tampilkan loading
                    daftarContainer.html(
                        '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat data...</div>'
                    );
                    container.show();

                    // Ambil data pelanggan
                    $.ajax({
                        url: '{{ route('data_pinjaman.pelangganByBotol', ':botolId') }}'.replace(
                            ':botolId', botolId),
                        type: 'GET',
                        success: function(data) {
                            if (data.length > 0) {
                                var html = '<ul class="list-group">';
                                data.forEach(function(pelanggan) {
                                    html +=
                                        '<li class="list-group-item d-flex flex-column py-2">';
                                    html += '<strong>' + pelanggan.nama + '</strong>';
                                    html += '<span class="small text-muted">' + (
                                        pelanggan.alamat || 'Alamat tidak tersedia'
                                    ) + '</span>';
                                    html += '<span class="small">' + (pelanggan.no_hp ||
                                        'No HP tidak tersedia') + '</span>';
                                    html += '<span class="small">Pinjaman terakhir: ' +
                                        pelanggan.tanggal_pinjaman_terakhir + '</span>';
                                    html +=
                                        '<span class="badge align-self-start mt-1 bg-' +
                                        (pelanggan.status_pinjaman_terakhir ===
                                            'Dipinjam' ? 'warning' : 'success') + '">';
                                    html += pelanggan.status_pinjaman_terakhir +
                                        '</span>';
                                    html += '</li>';
                                });
                                html += '</ul>';
                                daftarContainer.html(html);
                            } else {
                                daftarContainer.html(
                                    '<div class="text-center text-muted"><i class="fas fa-info-circle"></i> Belum ada pelanggan yang pernah meminjam botol ini.</div>'
                                );
                            }
                        },
                        error: function() {
                            daftarContainer.html(
                                '<div class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Gagal memuat data pelanggan.</div>'
                            );
                        }
                    });
                } else {
                    container.hide();
                }
            });
        });

        (function() {
            const penanggungJawabSelect = document.getElementById('penanggung_jawab_id');
            const inputNama = document.getElementById('nama');
            const errorNama = document.getElementById('nama_error');
            const btnSimpanPenanggungJawab = document.getElementById('btnSimpanPenanggungJawab');

            function refreshPenanggungJawabOptions(selectedId) {
                fetch("{{ route('penanggung_jawab.index') }}", {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        // rebuild options
                        const current = penanggungJawabSelect.value;
                        penanggungJawabSelect.innerHTML = '';
                        const optDefault = document.createElement('option');
                        optDefault.value = '';
                        optDefault.textContent = 'Pilih Penanggung Jawab';
                        penanggungJawabSelect.appendChild(optDefault);
                        data.forEach(item => {
                            const opt = document.createElement('option');
                            opt.value = item.id;
                            opt.textContent = item.nama;
                            penanggungJawabSelect.appendChild(opt);
                        });
                        const toSelect = selectedId || current;
                        if (toSelect) {
                            penanggungJawabSelect.value = toSelect;
                        }
                    })
                    .catch(() => {});
            }

            btnSimpanPenanggungJawab?.addEventListener('click', function() {
                const nama = (inputNama?.value || '').trim();
                errorNama.style.display = 'none';
                errorNama.textContent = '';
                inputNama.classList.remove('is-invalid');
                if (!nama) {
                    inputNama.classList.add('is-invalid');
                    errorNama.textContent = 'Nama penanggung jawab wajib diisi';
                    errorNama.style.display = 'block';
                    return;
                }

                fetch("{{ route('penanggung_jawab.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            nama: nama
                        })
                    })
                    .then(async res => {
                        if (res.ok) return res.json();
                        const payload = await res.json().catch(() => ({}));
                        throw payload;
                    })
                    .then(data => {
                        // close modal and refresh select
                        const modalEl = document.getElementById('modalPenanggungJawab');
                        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                        modal.hide();
                        inputNama.value = '';
                        refreshPenanggungJawabOptions(data.id);
                    })
                    .catch(err => {
                        const msg = err?.message || (err?.errors?.nama?.[0]) ||
                            'Gagal menyimpan penanggung jawab';
                        inputNama.classList.add('is-invalid');
                        errorNama.textContent = msg;
                        errorNama.style.display = 'block';
                    });
            });

            // initial populate if server didn't pass options
            if (!penanggungJawabSelect.querySelector('option[value]:not([value=""])')) {
                refreshPenanggungJawabOptions();
            }
        })();
    </script>
@endpush
