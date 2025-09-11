@extends('layouts.app')

@section('title', 'Crosscheck Penerimaan Botol')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                    <li class="breadcrumb-item"><a href="#">Isi Botol</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Crosscheck Penerimaan</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Crosscheck Penerimaan - TRX#{{ $transaksi->id }}</h5>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Tanggal Kirim:</strong>
                        {{ \Illuminate\Support\Carbon::parse($transaksi->tanggal_isi)->format('d-m-Y') }}
                    </div>
                    <form action="{{ route('transaksi_isi_botol.crosscheck.store', $transaksi->id) }}" method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nomor Botol</th>
                                        <th>Status Kirim</th>
                                        <th>Terima</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transaksi->details as $detail)
                                        <tr>
                                            <td>{{ optional($detail->botol)->nomor_botol }}</td>
                                            <td>
                                                @if ($detail->status_kirim == 1)
                                                    <span class="badge bg-success">Kembali</span>
                                                @else
                                                    <span class="badge bg-warning">Masuk Pabrik</span>
                                                @endif
                                            </td>
                                            <td>
                                                <input type="checkbox" name="received_ids[]" value="{{ $detail->id }}"
                                                    {{ $detail->status_kirim == 1 ? 'checked disabled' : '' }}>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan Crosscheck</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
