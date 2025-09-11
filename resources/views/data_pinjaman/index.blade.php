@extends('layouts.app')

@section('title', 'POS Karya Hutama Oxygen - Daftar Pinjaman')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Pinjaman</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Daftar Pinjaman</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between">
                <h5 class="mb-0">Daftar Pinjaman</h5>
                <a href="{{ route('data_pinjaman.create') }}" class="btn btn-primary mb-3">
                    <i class="bx bx-plus"></i> Tambah Pinjaman
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table" id="data_pinjaman-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Botol</th>
                                    <th>Nama Peminjam</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <!-- Modal Perbarui Status (reusable) -->
                    <div class="modal fade" id="perbaruiStatusModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" class="modal-content update-status-form" id="updateStatusForm">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Masukkan Tanggal Kembali</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="tanggal_pengembalian_input" class="form-label">Tanggal
                                            Pengembalian</label>
                                        <input type="date" name="tanggal_pengembalian" class="form-control"
                                            id="tanggal_pengembalian_input">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary simpan-status-btn" id="simpanStatusBtn">
                                        <i class="bx bx-save me-1"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                let table = $('#data_pinjaman-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('data_pinjaman.data') }}',
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                    },
                    order: [
                        [3, 'desc']
                    ],
                    columnDefs: [{
                        orderable: false,
                        targets: 0
                    }]
                });

                table.on('draw.dt', function() {
                    let info = table.page.info();
                    table.column(0, {
                            search: 'applied',
                            order: 'applied',
                            page: 'current'
                        })
                        .nodes()
                        .each(function(cell, i) {
                            cell.innerHTML = i + 1 + info.start;
                        });
                });
            });

            document.addEventListener('click', function(e) {
                const btn = e.target.closest('[data-action="open-update-status"]');
                if (!btn) return;
                const id = btn.getAttribute('data-id');
                const form = document.getElementById('updateStatusForm');
                form.action = '{{ url('/data_pinjaman/update-status') }}/' + id;
                document.getElementById('tanggal_pengembalian_input').value = '';
                const modal = new bootstrap.Modal(document.getElementById('perbaruiStatusModal'));
                modal.show();
            });

            document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('.simpan-status-btn');
                if (submitBtn) {
                    submitBtn.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...';
                    submitBtn.disabled = true;
                }
            });
        </script>
    @endpush
@endsection
