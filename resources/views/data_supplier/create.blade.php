@extends('layouts.app')

@section('title', 'Tambah Supplier')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Tambah Supplier</h5>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('data_supplier.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_supplier" class="form-label">Nama Supplier<span
                                    style="color:red">*</span></label>
                            <input type="text" id="nama_supplier" name="nama_supplier"
                                class="form-control @error('nama_supplier') is-invalid @enderror"
                                value="{{ old('nama_supplier') }}" required>
                            @error('nama_supplier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <a href="{{ route('data_supplier.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
