@extends('layouts.app')

@section('title', 'POS Karya Hutama Oxygen - Edit Pelanggan')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Pelanggan</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('data_pelanggan.index') }}">Daftar Pelanggan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Pelanggan</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Edit Pelanggan</h5>
            </div>
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <form action="{{ route('data_pelanggan.update', $data_pelanggan->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Pelanggan<span
                                            style="color: red;">*</span></label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                        id="nama" name="nama" value="{{ old('nama', $data_pelanggan->nama) }}" required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <label for="alamat" class="form-label">Alamat<span
                                            style="color: red;">*</span></label>
                                    <input type="text" class="form-control @error('alamat') is-invalid @enderror"
                                        id="alamat" name="alamat" value="{{ old('alamat', $data_pelanggan->alamat) }}" required>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <label for="no_hp" class="form-label">Kontak<span
                                            style="color: red;">*</span></label>
                                    <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                                        id="no_hp" name="no_hp" value="{{ old('no_hp', $data_pelanggan->no_hp) }}" required>
                                    @error('no_hp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
