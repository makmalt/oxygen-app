@extends('layouts.app')

@section('title', 'Dashboard')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row g-3">
                <div class="col-md-3">
                    <a class="btn btn-primary w-100" href="{{ route('data_pinjaman.create') }}">Form Pinjaman</a>
                </div>
                <div class="col-md-3">
                    <a class="btn btn-outline-primary w-100" href="{{ route('transaksi_isi_botol.create') }}">Kirim Botol ke
                        Pabrik</a>
                </div>
                <div class="col-md-3">
                    <a class="btn btn-outline-secondary w-100" href="{{ route('data_botol.index') }}">Data Botol</a>
                </div>
                <div class="col-md-3">
                    <a class="btn btn-outline-secondary w-100" href="{{ route('data_pinjaman.index') }}">Data Pinjaman</a>
                </div>
            </div>
        </div>
    </div>
@endsection
