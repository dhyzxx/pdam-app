@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-file-earmark-excel"></i> Ekspor Tagihan Bulanan</div>
    <div class="card-body">
        <form action="{{ route('tagihan.prosesExportBulan') }}" method="POST" id="formExportBulanan">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="bulan_export" class="form-label">Pilih Bulan & Tahun <span class="text-danger">*</span></label>
                        <input type="month" class="form-control @error('bulan_export') is-invalid @enderror" 
                               id="bulan_export" name="bulan_export" 
                               value="{{ old('bulan_export', request('target_bulan', date('Y-m'))) }}" required>
                        @error('bulan_export')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success"><i class="bi bi-download"></i> Ekspor ke Excel</button>
            <a href="{{ route('tagihan.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection