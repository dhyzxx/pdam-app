@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-clock-history"></i> Riwayat Pembayaran</div>
    <div class="card-body">
        <form method="GET" action="{{ route('tagihan.riwayat') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="search_pelanggan_riwayat" class="form-control form-control-sm" placeholder="Cari Nama/ID Pelanggan..." value="{{ request('search_pelanggan_riwayat') }}">
                </div>
                <div class="col-md-4">
                     <input type="month" name="filter_bulan_riwayat" class="form-control form-control-sm" value="{{ request('filter_bulan_riwayat') }}" placeholder="Filter Bulan Tagihan">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary btn-sm w-100" type="submit"><i class="bi bi-filter"></i> Filter</button>
                </div>
            </div>
             @if(request('search_pelanggan_riwayat') || request('filter_bulan_riwayat'))
                <div class="mt-2">
                     <a href="{{ route('tagihan.riwayat') }}" class="btn btn-outline-danger btn-sm"><i class="bi bi-x-circle"></i> Reset Filter</a>
                </div>
            @endif
        </form>

        @if($pembayarans->isEmpty())
            <div class="alert alert-info text-center">
                Tidak ada data riwayat pembayaran yang ditemukan.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tgl. Bayar</th>
                            <th scope="col">ID Pelanggan</th>
                            <th scope="col">Nama Pelanggan</th>
                            <th scope="col">Bulan Tagihan</th>
                            <th scope="col">Jumlah Bayar</th>
                            <th scope="col" style="min-width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pembayarans as $index => $pembayaran)
                        <tr>
                            <th scope="row">{{ $pembayarans->firstItem() + $index }}</th>
                            <td>{{ \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->isoFormat('DD MMM YYYY') }}</td>
                            <td>
                                <a href="{{ route('pelanggan.show', $pembayaran->tagihan->pelanggan_id) }}" title="Lihat Detail Pelanggan">{{ $pembayaran->tagihan->pelanggan->id_pelanggan }}</a>
                            </td>
                            <td>{{ $pembayaran->tagihan->pelanggan->nama_pelanggan }}</td>
                            <td>{{ \Carbon\Carbon::parse($pembayaran->tagihan->bulan_tagihan . '-01')->isoFormat('MMMM YYYY') }}</td>
                            <td>Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('pelanggan.show', $pembayaran->pelanggan_id) }}#tagihan-{{$pembayaran->tagihan_id}}" class="btn btn-sm btn-outline-info me-1" title="Lihat Detail Tagihan">
                                    <i class="bi bi-receipt-cutoff"></i> Detail
                                </a>
                                <a href="{{ route('pembayaran.cetakBuktiPdf', $pembayaran->id) }}" class="btn btn-sm btn-outline-success" title="Cetak Bukti Pembayaran" target="_blank">
                                    <i class="bi bi-printer-fill"></i> Bukti
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $pembayarans->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection