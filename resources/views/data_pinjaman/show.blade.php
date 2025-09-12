@extends('layouts.app')

@section('title', 'Detail Pinjaman')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="{{ route('data_pinjaman.index') }}">Pinjaman</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Detail Pinjaman #{{ $data_pinjaman->id }}</h5>
                <a href="{{ route('data_pinjaman.index') }}" class="btn btn-secondary"><i class="bx bx-left-arrow-alt"></i>
                    Kembali</a>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card mb-3">
                        <div class="card-header">Informasi Botol</div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Nomor Botol</dt>
                                <dd class="col-sm-8">{{ optional($data_pinjaman->botol)->nomor_botol ?? '-' }}</dd>

                                <dt class="col-sm-4">Status Isi</dt>
                                <dd class="col-sm-8">
                                    @php($statusIsi = optional($data_pinjaman->botol)->status_isi)
                                    @if (strtolower((string) $statusIsi) === 'kosong')
                                        <span class="badge bg-warning text-dark">{{ $statusIsi }}</span>
                                    @elseif (!empty($statusIsi))
                                        <span class="badge bg-success">{{ $statusIsi }}</span>
                                    @else
                                        -
                                    @endif
                                </dd>

                                <dt class="col-sm-4">Status Pinjaman</dt>
                                <dd class="col-sm-8">
                                    @if (is_null($data_pinjaman->tanggal_pengembalian))
                                        <span class="badge bg-warning text-dark">Dipinjam</span>
                                    @else
                                        <span class="badge bg-success">Sudah Kembali</span>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card mb-3">
                        <div class="card-header">Peminjam</div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Nama</dt>
                                <dd class="col-sm-8">{{ optional($data_pinjaman->pelanggan)->nama ?? '-' }}</dd>
                                <dt class="col-sm-4">Alamat</dt>
                                <dd class="col-sm-8">{{ optional($data_pinjaman->pelanggan)->alamat ?? '-' }}</dd>
                                <dt class="col-sm-4">No. HP</dt>
                                <dd class="col-sm-8">{{ optional($data_pinjaman->pelanggan)->no_hp ?? '-' }}</dd>
                                <dt class="col-sm-4">Penanggung Jawab</dt>
                                <dd class="col-sm-8">{{ optional($data_pinjaman->penanggungJawab)->nama ?? '-' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">Timeline</div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-3">Tanggal Pinjam</dt>
                                <dd class="col-sm-9">
                                    {{ optional($data_pinjaman->tanggal_pinjaman)->format('d F Y') ?? '-' }}</dd>
                                <dt class="col-sm-3">Tanggal Kembali</dt>
                                <dd class="col-sm-9">
                                    {{ optional($data_pinjaman->tanggal_pengembalian)->format('d F Y') ?? '-' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
