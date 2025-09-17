@extends('layouts.app')

@section('title', 'POS Karya Hutama Oxygen - Edit Botol')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Botol</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('data_botol.index') }}">Daftar Botol</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Botol</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Edit Botol</h5>
            </div>
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <form action="{{ route('data_botol.update', $data_botol->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nomor_botol" class="form-label">Nomor Botol<span
                                            style="color: red;">*</span></label>
                                    <input type="text" class="form-control @error('nomor_botol') is-invalid @enderror"
                                        id="nomor_botol" name="nomor_botol"
                                        value="{{ old('nomor_botol', $data_botol->nomor_botol) }}" required>
                                    @error('nomor_botol')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="uniq" class="form-label">Kode<span style="color: red;">*</span></label>
                                    <input type="text" class="form-control @error('uniq') is-invalid @enderror"
                                        id="uniq" name="uniq" value="{{ old('uniq', $data_botol->uniq) }}" required>
                                    @error('uniq')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="jenis_botol" class="form-label">Jenis Botol<span
                                            style="color: red;">*</span></label>
                                    <select class="form-control @error('jenis_botol') is-invalid @enderror" id="jenis_botol"
                                        name="jenis_botol" required>
                                        <option value="{{ $data_botol->jenis_botol }}">{{ $data_botol->jenis_botol }}
                                        </option>
                                        @foreach ($jenis as $item)
                                            <option value="{{ $item->nama_jenis }}"
                                                {{ old('jenis_botol') == $item->nama_jenis ? 'selected' : '' }}>
                                                {{ $item->nama_jenis }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jenis_botol')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <a href="{{ route('data_botol.index') }}" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
