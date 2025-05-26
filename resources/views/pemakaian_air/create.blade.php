@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-droplet-half"></i> Catat Pemakaian Air Baru untuk <strong>{{ $pelanggan->nama_pelanggan }} ({{ $pelanggan->id_pelanggan }})</strong>
    </div>
    <div class="card-body">
        <form action="{{ route('pemakaian_air.store', $pelanggan->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="bulan" class="form-label">Bulan Pemakaian <span class="text-danger">*</span></label>
                <input type="month" class="form-control @error('bulan') is-invalid @enderror" id="bulan" name="bulan" value="{{ old('bulan', date('Y-m')) }}" required>
                @error('bulan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="meter_awal" class="form-label">Meter Awal (m³) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" class="form-control @error('meter_awal') is-invalid @enderror" id="meter_awal" name="meter_awal" value="{{ old('meter_awal') }}" required>
                @error('meter_awal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="meter_akhir" class="form-label">Meter Akhir (m³) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" class="form-control @error('meter_akhir') is-invalid @enderror" id="meter_akhir" name="meter_akhir" value="{{ old('meter_akhir') }}" required>
                @error('meter_akhir')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <p class="text-muted small">Tarif: Rp 1.500 per m³. Tagihan akan dibuat secara otomatis.</p>
            <a href="{{ route('pelanggan.show', $pelanggan->id) }}" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Batal</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Pemakaian</button>
        </form>
    </div>
</div>
@endsection