@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-person-plus-fill"></i> Tambah Pelanggan Baru</div>
    <div class="card-body">
        <form action="{{ route('pelanggan.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="id_pelanggan" class="form-label">ID Pelanggan <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('id_pelanggan') is-invalid @enderror" id="id_pelanggan" name="id_pelanggan" value="{{ old('id_pelanggan') }}" required>
                @error('id_pelanggan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="nama_pelanggan" class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama_pelanggan') is-invalid @enderror" id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}" required>
                @error('nama_pelanggan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Kembali</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
        </form>
    </div>
</div>
@endsection