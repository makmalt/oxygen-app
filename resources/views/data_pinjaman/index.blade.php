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
                            <tbody>
                                @forelse($data_pinjamans as $data_pinjaman)
                                    <tr>
                                        <td></td>
                                        <td>{{ $data_pinjaman->botol->nomor_botol }}</td>
                                        <td>{{ $data_pinjaman->pelanggan->nama }}</td>
                                        <td>{{ $data_pinjaman->tanggal_pinjaman }}</td>
                                        <td>{{ $data_pinjaman->tanggal_pengembalian }}</td>
                                        <td>
                                            @if (is_null($data_pinjaman->tanggal_pengembalian))
                                                <span class="badge bg-warning text-warning text-black">Dipinjam</span>
                                            @else
                                                <span class="badge bg-success">Sudah Kembali</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#perbaruiStatusModal{{ $data_pinjaman->id }}">
                                                <i class="bx bx-refresh"></i> Perbarui
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada pinjaman</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Tempatkan seluruh modal di luar .table-responsive --}}
                    @foreach ($data_pinjamans as $data_pinjaman)
                        <!-- Modal Perbarui Status -->
                        <div class="modal fade" id="perbaruiStatusModal{{ $data_pinjaman->id }}" tabindex="-1"
                            aria-labelledby="perbaruiStatusModalLabel{{ $data_pinjaman->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('data_pinjaman.updateStatus', $data_pinjaman->id) }}" method="POST"
                                    class="modal-content update-status-form" id="updateStatusForm{{ $data_pinjaman->id }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="perbaruiStatusModalLabel{{ $data_pinjaman->id }}">
                                            Masukkan Tanggal Kembali
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">

                                        <div class="alert alert-info" role="alert">
                                            <i class="bx bx-info-circle me-2"></i>
                                            Status saat ini:
                                            @if ($data_pinjaman->status_pinjaman == 0)
                                                <span class="badge bg-warning text-dark">Dipinjam</span>
                                            @else
                                                <span class="badge bg-success text-dark">Sudah Kembali</span>
                                            @endif
                                        </div>

                                        {{-- Input tanggal pengembalian --}}
                                        <div class="mb-3">
                                            <label for="tanggal_pengembalian{{ $data_pinjaman->id }}" class="form-label">
                                                Tanggal Pengembalian
                                            </label>
                                            <input type="date" name="tanggal_pengembalian" class="form-control"
                                                id="tanggal_pengembalian{{ $data_pinjaman->id }}"
                                                value="{{ $data_pinjaman->tanggal_pengembalian }}">
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary simpan-status-btn"
                                            id="simpanStatusBtn{{ $data_pinjaman->id }}">
                                            <i class="bx bx-save me-1"></i> Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                let table = $('#data_pinjaman-table').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                    },
                    order: [
                        [3, 'desc']
                    ], // kolom Tanggal Pinjam DESC
                    columnDefs: [{
                            orderable: false,
                            targets: 0
                        } // kolom No tidak ikut sort
                    ]
                });

                // Auto-generate nomor di kolom pertama
                table.on('order.dt search.dt', function() {
                    let i = 1;
                    table.cells(null, 0, {
                        search: 'applied',
                        order: 'applied'
                    }).every(function() {
                        this.data(i++);
                    });
                }).draw();
            });

            document.addEventListener('DOMContentLoaded', function() {
                // Get all forms with class 'update-status-form'
                const forms = document.querySelectorAll('.update-status-form');

                forms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        const submitBtn = this.querySelector('.simpan-status-btn');
                        if (submitBtn) {
                            submitBtn.innerHTML =
                                '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...';
                            submitBtn.disabled = true;
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
