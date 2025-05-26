@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-receipt"></i> Daftar Semua Tagihan</div>
    <a href="{{ route('tagihan.formExportBulan') }}" class="btn btn-info btn-sm"><i class="bi bi-file-earmark-excel"></i> Ekspor Tagihan per Bulan</a>
    <div class="card-body">
        <form method="GET" action="{{ route('tagihan.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search_pelanggan" class="form-control form-control-sm" placeholder="Cari Nama/ID Pelanggan..." value="{{ request('search_pelanggan') }}">
                </div>
                <div class="col-md-3">
                    <input type="month" name="filter_bulan" class="form-control form-control-sm" value="{{ request('filter_bulan') }}">
                </div>
                <div class="col-md-3">
                    <select name="filter_status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="Belum Lunas" {{ request('filter_status') == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="Lunas" {{ request('filter_status') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary btn-sm w-100" type="submit"><i class="bi bi-filter"></i> Filter</button>
                </div>
            </div>
             @if(request('search_pelanggan') || request('filter_bulan') || request('filter_status'))
                <div class="mt-2">
                     <a href="{{ route('tagihan.index') }}" class="btn btn-outline-danger btn-sm"><i class="bi bi-x-circle"></i> Reset Filter</a>
                </div>
            @endif
        </form>

        @if($tagihans->isEmpty())
            <div class="alert alert-info text-center">
                Tidak ada data tagihan yang sesuai dengan filter atau belum ada tagihan sama sekali.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">ID Pelanggan</th>
                            <th scope="col">Nama Pelanggan</th>
                            <th scope="col">Bulan Tagihan</th>
                            <th scope="col">Volume (m³)</th>
                            <th scope="col">Jumlah Tagihan</th>
                            <th scope="col">Pajak</th>
                            <th scope="col">Status</th>
                            <th scope="col" style="min-width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tagihans as $index => $tagihan)
                        <tr id="tagihan-{{$tagihan->id}}">
                            <th scope="row">{{ $tagihans->firstItem() + $index }}</th>
                            <td>
                                <a href="{{ route('pelanggan.show', $tagihan->pelanggan_id) }}" title="Lihat Detail Pelanggan">{{ $tagihan->pelanggan->id_pelanggan }}</a>
                            </td>
                            <td>{{ $tagihan->pelanggan->nama_pelanggan }}</td>
                            <td>{{ \Carbon\Carbon::parse($tagihan->bulan_tagihan . '-01')->isoFormat('MMMM YYYY') }}</td>
                            <td>{{ $tagihan->pemakaianAir ? number_format($tagihan->pemakaianAir->volume_pemakaian, 2, ',', '.') : '-' }}</td>
                            <td>Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}</td>
                            <td>
                                Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}
                                @if($tagihan->jumlah_pajak > 0)
                                    <br><small class="text-muted">(Termasuk Pajak Rp {{ number_format($tagihan->jumlah_pajak, 0, ',', '.') }})</small>
                                @endif
                            </td>
                            <td>
                                @if ($tagihan->status_pembayaran == 'Lunas')
                                    <span class="badge bg-success">{{ $tagihan->status_pembayaran }}</span>
                                    @if($tagihan->pembayaran)
                                    <small class="d-block text-muted">Tgl: {{ \Carbon\Carbon::parse($tagihan->pembayaran->tanggal_pembayaran)->isoFormat('DD MMM YYYY') }}</small>
                                    @endif
                                @else
                                    <span class="badge bg-danger">{{ $tagihan->status_pembayaran }}</span>
                                @endif
                            </td>
                            <td class="action-icons">
                                @if ($tagihan->status_pembayaran == 'Belum Lunas')
                                <a href="{{ route('tagihan.bayar', $tagihan->id) }}" class="btn btn-sm btn-outline-success me-1" title="Bayar Tagihan"><i class="bi bi-cash-coin"></i> Bayar</a>
                                @else
                                    @if ($tagihan->pembayaran)
                                    <a href="{{ route('pembayaran.cetakBuktiPdf', $tagihan->pembayaran->id) }}" class="btn btn-sm btn-outline-success me-1" title="Cetak Bukti Pembayaran" target="_blank"><i class="bi bi-award-fill"></i> Bukti</a>
                                    @else
                                    <span class="text-muted me-1"><i class="bi bi-check-circle-fill text-success"></i> Lunas</span>
                                    @endif
                                @endif
                                <a href="{{ route('tagihan.cetakPdf', $tagihan->id) }}" class="btn btn-sm btn-outline-info" title="Cetak Tagihan PDF" target="_blank"><i class="bi bi-printer-fill"></i> Tagihan</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $tagihans->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection