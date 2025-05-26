@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-person-badge"></i> Detail Pelanggan</span>
                <a href="{{ route('pelanggan.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali ke Daftar</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>ID Pelanggan:</strong> {{ $pelanggan->id_pelanggan }}</p>
                        <p><strong>Nama Pelanggan:</strong> {{ $pelanggan->nama_pelanggan }}</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{ route('pemakaian_air.create', $pelanggan->id) }}" class="btn btn-success btn-sm mb-2"><i class="bi bi-droplet-half"></i> Catat Pemakaian Baru</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-moisture"></i> Rekap Pemakaian Air</div>
            <div class="card-body">
                @if($pelanggan->pemakaianAir->isEmpty())
                    <div class="alert alert-info text-center">Belum ada data pemakaian air untuk pelanggan ini.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th>Meter Awal (m³)</th>
                                    <th>Meter Akhir (m³)</th>
                                    <th>Volume Pemakaian (m³)</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pelanggan->pemakaianAir->sortByDesc('bulan') as $pemakaian)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($pemakaian->bulan . '-01')->isoFormat('MMMM YYYY') }}</td>
                                    <td>{{ number_format($pemakaian->meter_awal, 2, ',', '.') }}</td>
                                    <td>{{ number_format($pemakaian->meter_akhir, 2, ',', '.') }}</td>
                                    <td>{{ number_format($pemakaian->volume_pemakaian, 2, ',', '.') }}</td>
                                    <td class="action-icons">
                                        <a href="{{ route('pemakaian_air.edit', $pemakaian->id) }}" class="text-primary" title="Edit Pemakaian"><i class="bi bi-pencil-fill"></i></a>
                                        @if(!$pemakaian->tagihan || $pemakaian->tagihan->status_pembayaran == 'Belum Lunas')
                                        <form action="{{ route('pemakaian_air.destroy', $pemakaian->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pemakaian ini? Tagihan terkait (jika belum lunas) juga akan terhapus.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0" title="Hapus Pemakaian"><i class="bi bi-trash-fill"></i></button>
                                        </form>
                                        @else
                                        <span class="text-muted" title="Tidak dapat dihapus, tagihan sudah lunas"><i class="bi bi-trash-fill"></i></span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header"><i class="bi bi-file-earmark-text-fill"></i> Rekap Tagihan</div>
            <div class="card-body">
                @if($pelanggan->tagihan->isEmpty())
                    <div class="alert alert-info text-center">Belum ada data tagihan untuk pelanggan ini.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Bulan Tagihan</th>
                                    <th>Volume (m³)</th>
                                    <th>Jumlah Tagihan</th>
                                    <th>Status</th>
                                    <th>Pajak</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pelanggan->tagihan->sortByDesc('bulan_tagihan') as $tagihan)
                                <tr id="tagihan-{{$tagihan->id}}">
                                    <td>{{ \Carbon\Carbon::parse($tagihan->bulan_tagihan . '-01')->isoFormat('MMMM YYYY') }}</td>
                                    <td>{{ $tagihan->pemakaianAir ? number_format($tagihan->pemakaianAir->volume_pemakaian, 2, ',', '.') : '-' }}</td>
                                    <td>Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($tagihan->status_pembayaran == 'Lunas')
                                            <span class="badge bg-success">{{ $tagihan->status_pembayaran }}</span>
                                            @if ($tagihan->pembayaran)
                                                <small class="d-block text-muted">Tgl: {{ \Carbon\Carbon::parse($tagihan->pembayaran->tanggal_pembayaran)->isoFormat('DD MMM YYYY') }}</small>
                                            @endif
                                        @else
                                            <span class="badge bg-danger">{{ $tagihan->status_pembayaran }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}
                                        @if($tagihan->jumlah_pajak > 0)
                                            <br><small class="text-muted">(Termasuk Pajak Rp {{ number_format($tagihan->jumlah_pajak, 0, ',', '.') }})</small>
                                        @endif
                                    </td>
                                    <td class="action-icons">
                                        @if ($tagihan->status_pembayaran == 'Belum Lunas')
                                        <a href="{{ route('tagihan.bayar', $tagihan->id) }}" class="btn btn-sm btn-outline-success" title="Bayar Tagihan"><i class="bi bi-cash-coin"></i> Bayar</a>
                                        @else
                                            @if ($tagihan->pembayaran)
                                            <a href="{{ route('pembayaran.cetakBuktiPdf', $tagihan->pembayaran->id) }}" class="btn btn-sm btn-outline-success" title="Cetak Bukti Pembayaran" target="_blank"><i class="bi bi-award-fill"></i> Bukti</a>
                                            @else
                                            <span class="text-muted"><i class="bi bi-check-circle-fill text-success"></i> Lunas</span>
                                            @endif
                                        @endif
                                        <a href="{{ route('tagihan.cetakPdf', $tagihan->id) }}" class="btn btn-sm btn-outline-info ms-1" title="Cetak Tagihan PDF" target="_blank"><i class="bi bi-printer-fill"></i> Tagihan</a>
                                    </td>
                                    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection