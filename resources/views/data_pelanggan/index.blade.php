@extends('layouts.app')

@section('title', 'POS Karya Hutama Oxygen - Daftar Pelanggan')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Pelanggan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Daftar Pelanggan</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between">
                <h5 class="mb-0">Daftar Pelanggan</h5>
                <a href="{{ route('data_pelanggan.create') }}" class="btn btn-primary mb-3">
                    <i class="bx bx-plus"></i> Tambah Pelanggan
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table" id="data_pelanggan-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Alamat</th>
                                    <th>Kontak</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data_pelanggans as $data_pelanggan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data_pelanggan->nama }}</td>
                                        <td>{{ $data_pelanggan->alamat }}</td>
                                        <td>{{ $data_pelanggan->no_hp }}</td>
                                        <td>
                                            <a href="{{ route('data_pelanggan.show', $data_pelanggan->id) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="bx bx-detail me-1"></i> Detail
                                            </a>
                                            <a href="{{ route('data_pelanggan.edit', $data_pelanggan->id) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="bx bx-edit me-1"></i> Edit
                                            </a>
                                            <form action="{{ route('data_pelanggan.destroy', $data_pelanggan->id) }}"
                                                method="POST" style="display:inline-block"
                                                onsubmit="return confirm('Hapus pelanggan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bx bx-trash me-1"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada pelanggan</td>
                                    </tr>
                                @endforelse
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
                $('#data_pelanggan-table').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                    }
                });
            });
        </script>
    @endpush
@endsection
