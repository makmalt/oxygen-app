@extends('layouts.app')

@section('title', 'POS Karya Hutama Oxygen - Daftar Botol')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Botol</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Daftar Botol</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between">
                <h5 class="mb-0">Daftar Botol</h5>
                <a href="{{ route('data_botol.create') }}" class="btn btn-primary mb-3">
                    <i class="bx bx-plus"></i> Tambah Botol
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="mb-3 d-flex justify-content-center gap-2">
                        <a href="{{ route('data_botol.index') }}"
                            class="btn btn-outline-secondary btn-sm {{ request('status') == null ? 'active' : '' }}">Semua</a>
                        <a href="{{ route('data_botol.index', ['status' => 'dipinjam']) }}"
                            class="btn btn-outline-warning btn-sm {{ strtolower(request('status')) == 'dipinjam' ? 'active' : '' }}">Dipinjam</a>
                        <a href="{{ route('data_botol.index', ['status' => 'Sudah Kembali']) }}"
                            class="btn btn-outline-success btn-sm {{ strtolower(request('status')) == 'sudah kembali' ? 'active' : '' }}">Sudah
                            Kembali</a>
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table" id="data_botol-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Botol</th>
                                    <th>Kode</th>
                                    <th>Status Pinjaman</th>
                                    <th>Jenis Botol</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data_botols as $data_botol)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data_botol->nomor_botol }}</td>
                                        <td>{{ $data_botol->uniq }}</td>
                                        <td>
                                            @if ($data_botol->status_pinjaman == 0)
                                                <span class="badge bg-success">Sudah Kembali</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Dipinjam</span>
                                            @endif
                                        </td>
                                        <td>{{ $data_botol->jenis_botol }}</td>
                                        <td>
                                            <a href="{{ route('data_botol.show', $data_botol->id) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="bx bx-detail me-1"></i> Lihat
                                            </a>
                                            <a href="{{ route('data_botol.edit', $data_botol->id) }}"
                                                class="btn btn-warning btn-sm">Edit
                                            </a>
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#perbaruiStatusModal{{ $data_botol->id }}">
                                                <i class="bx bx-refresh"></i> Perbarui
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Tempatkan seluruh modal di luar .table-responsive --}}
                    @foreach ($data_botols as $data_botol)
                        <!-- Modal Perbarui Status -->
                        <div class="modal fade" id="perbaruiStatusModal{{ $data_botol->id }}" tabindex="-1"
                            aria-labelledby="perbaruiStatusModalLabel{{ $data_botol->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('data_botol.updateStatus', $data_botol->id) }}" method="POST"
                                    class="modal-content update-status-form" id="updateStatusForm{{ $data_botol->id }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="perbaruiStatusModalLabel{{ $data_botol->id }}">
                                            Perbarui Status Botol
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">

                                        <div class="alert alert-info" role="alert">
                                            <i class="bx bx-info-circle me-2"></i>
                                            Status saat ini:
                                            @if ($data_botol->status_pinjaman)
                                                <span class="badge bg-warning text-dark">Dipinjam</span>
                                            @else
                                                <span class="badge bg-success text-dark">Sudah Kembali</span>
                                            @endif
                                        </div>

                                        <div class="form-check">
                                            <input type="hidden" name="status_isi" value="0">
                                            <input class="form-check-input" type="checkbox" name="status_isi" value="1"
                                                {{ $data_botol->status_isi ? 'checked' : '' }}
                                                id="status_isi{{ $data_botol->id }}">
                                            <label class="form-check-label" for="status_isi{{ $data_botol->id }}">
                                                Tandai sebagai Sudah dikembalikan
                                            </label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary simpan-status-btn"
                                            id="simpanStatusBtn{{ $data_botol->id }}">
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
                $('#data_botol-table').DataTable({ // index kolom dalam array
                });
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
