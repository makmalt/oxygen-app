@extends('layouts.app')

@section('title', 'POS Karya Hutama Oxygen - Dashboard')
<style>
    #body-tagihan tr:hover {
        background-color: #f2f2f2;
    }
</style>

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Dashboard</h2>

        {{-- Summary --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card p-3">
                    <div>Total Botol</div>
                    <h2>{{ $totalBotol }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <div>Jumlah Botol Isi</div>
                    <h2>{{ $totalBotolIsi }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <div>Jumlah Botol Kosong</div>
                    <h2>{{ $totalBotolKosong }}</h2>
                </div>
            </div>
        </div>

        {{-- Botol Kosong --}}
        <div class="card">
            <div class="card-header">
                <h5>Daftar Botol Kosong</h5>
            </div>
            <div class="card-body">
                <table class="table" id="table-botol-kosong">
                    <thead>
                        <tr>
                            <th>Nomor Botol</th>
                            <th>Status Isi</th>
                            <th>Status Pinjaman</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($botolKosongs as $botol)
                            <tr>
                                <td>{{ $botol->nomor_botol }}</td>
                                <td>
                                    @if (strtolower($botol->status_isi) === 'kosong')
                                        <span class="badge bg-warning text-dark">{{ $botol->status_isi }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $botol->status_isi }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($botol->status_pinjaman)
                                        <span class="badge bg-danger">Dipinjam</span>
                                    @else
                                        <span class="badge bg-success">Kembali</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Botol Masuk Pabrik --}}
        <div class="card mt-4">
            <div class="card-header">
                <h5>Daftar Botol Masuk Pabrik</h5>
            </div>
            <div class="card-body">
                <table class="table" id="table-botol-pabrik">
                    <thead>
                        <tr>
                            <th>Nomor Botol</th>
                            <th>Status</th>
                            <th>Tanggal Dikirim</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($botolMasukPabrik as $botol)
                            <tr>
                                <td>{{ $botol->nomor_botol }}</td>
                                <td>
                                    @if (strtolower($botol->status_isi) === 'masuk pabrik')
                                        <span class="badge bg-warning text-dark">{{ $botol->status_isi }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $botol->status_isi }}</span>
                                    @endif
                                </td>
                                <td>
                                    {{ optional($botol->details?->transaksi)->tanggal_isi
                                        ? \Carbon\Carbon::parse($botol->details->transaksi->tanggal_isi)->format('d-m-Y')
                                        : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Aktivitas --}}
        <div class="card mt-4">
            <div class="card-header">
                <h5>Daftar Aktivitas Botol</h5>
            </div>
            <div class="card-body">
                <table class="table" id="activities-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Aktivitas</th>
                            <th>Nomor Botol</th>
                            <th>Deskripsi</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#table-botol-kosong').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                    }
                });

                $('#table-botol-pabrik').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                    }
                });

                $('#activities-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('dashboard.data') }}',
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'activityName',
                            name: 'activityName'
                        },
                        {
                            data: 'no_botol',
                            name: 'no_botol'
                        },
                        {
                            data: 'description',
                            name: 'description'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                    ],
                    order: [
                        [4, 'desc']
                    ]
                });
            });
        </script>
    @endpush
