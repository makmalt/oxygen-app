@extends('layouts.app')

@section('title', 'Daftar Transaksi Isi Botol')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Isi Botol</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between">
                <h5 class="mb-0">Daftar Transaksi Isi Botol</h5>
                <a href="{{ route('transaksi_isi_botol.create') }}" class="btn btn-primary mb-3">
                    <i class="bx bx-plus"></i> Buat Pengiriman
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table" id="transaksi-isi-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Kirim</th>
                                    <th>Total Botol</th>
                                    <th>Sudah Kembali</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                let table = $('#transaksi-isi-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('transaksi_isi_botol.data') }}',
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                    },
                    order: [
                        [1, 'desc']
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
        </script>
    @endpush
@endsection
