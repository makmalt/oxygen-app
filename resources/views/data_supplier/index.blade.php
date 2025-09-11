@extends('layouts.app')

@section('title', 'Data Supplier')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Data Supplier</h5>
                <a href="{{ route('data_supplier.create') }}" class="btn btn-primary"><i class="bx bx-plus"></i> Tambah
                    Supplier</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table" id="supplier-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Supplier</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $s)
                                    <tr>
                                        <td></td>
                                        <td>{{ $s->nama_supplier }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                let table = $('#supplier-table').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                    },
                    order: [],
                    columnDefs: [{
                        orderable: false,
                        targets: 0
                    }]
                });
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
        </script>
    @endpush
@endsection
