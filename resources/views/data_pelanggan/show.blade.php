@extends('layouts.app')

@section('title', 'POS Karya Hutama Oxygen - Detail Pelanggan')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Pelanggan</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('data_pelanggan.index') }}">Daftar Pelanggan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail Pelanggan</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Detail Pelanggan</h5>
            </div>
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="fw-semibold">Nama Pelanggan</h6>
                                <p class="mb-0">{{ $data_pelanggan->nama }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="fw-semibold">Alamat Pelanggan</h6>
                                <p class="mb-0">{{ $data_pelanggan->alamat }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="fw-semibold">Kontak Pelanggan</h6>
                                <p class="mb-0">{{ $data_pelanggan->no_hp }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Riwayat Pinjaman -->
                    <div class="mt-4">
                        <h5 class="fw-semibold mb-3">Riwayat Pinjaman</h5>
                        <div class="table-responsive">
                            <table class="table" id="riwayat-stok">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3">Tanggal</th>
                                        <th class="px-4 py-3">Nomor Botol</th>
                                        <th class="px-4 py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($riwayat as $riwayat)
                                        <tr>
                                            <td>{{ $riwayat->tanggal_pinjaman->format('d F Y') }}</td>
                                            <td>{{ $riwayat->botol->nomor_botol }}</td>
                                            <td>
                                                @if (is_null($riwayat->tanggal_pengembalian))
                                                    <span
                                                        class="badge bg-{{ $riwayat->botol->status_pinjaman == 0 ? 'success' : 'warning' }}">
                                                        {{ $riwayat->botol->status_pinjaman == 0 ? 'Sudah Kembali' : 'Dipinjam' }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-success">Sudah Kembali</span>
                                                @endif
                                            </td>

                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#riwayat-stok').DataTable({
                    order: [
                        [0, 'desc']
                    ]
                });

            });
        </script>
    @endpush
@endsection
