@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-cash-coin"></i> Proses Pembayaran Tagihan</div>
    <div class="card-body">
        <h5>Detail Tagihan:</h5>
        <div class="row mb-3">
            <div class="col-md-6">
                <p><strong>Pelanggan:</strong> {{ $tagihan->pelanggan->nama_pelanggan }} ({{ $tagihan->pelanggan->id_pelanggan }})</p>
                <p><strong>Bulan Tagihan:</strong> {{ \Carbon\Carbon::parse($tagihan->bulan_tagihan . '-01')->isoFormat('MMMM YYYY') }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Volume Pemakaian:</strong> {{ $tagihan->pemakaianAir ? number_format($tagihan->pemakaianAir->volume_pemakaian, 2, ',', '.') . ' m³' : '-' }}</p>
                <p><strong>Jumlah Tagihan:</strong> <span class="fw-bold fs-5 text-danger">Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}</span></p>
            </div>
        </div>
        <hr>
        <form action="{{ route('tagihan.prosesBayar', $tagihan->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="tanggal_pembayaran" class="form-label">Tanggal Pembayaran <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('tanggal_pembayaran') is-invalid @enderror" id="tanggal_pembayaran" name="tanggal_pembayaran" value="{{ old('tanggal_pembayaran', date('Y-m-d')) }}" required>
                @error('tanggal_pembayaran')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="jumlah_bayar" class="form-label">Jumlah Bayar <span class="text-danger">*</span></label>
                <input type="number" step="0.01" class="form-control @error('jumlah_bayar') is-invalid @enderror" id="jumlah_bayar" name="jumlah_bayar" value="{{ old('jumlah_bayar', $tagihan->jumlah_tagihan) }}" required readonly>
                 <small class="form-text text-muted">Jumlah bayar harus sama dengan jumlah tagihan.</small>
                @error('jumlah_bayar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Kembali</a>
            <button type="submit" class="btn btn-success"><i class="bi bi-check-circle-fill"></i> Konfirmasi Pembayaran</button>
        </form>
    </div>
</div>
@endsection