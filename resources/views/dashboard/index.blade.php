@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <h3 class="mb-4"><i class="bi bi-speedometer2"></i> Dashboard</h3>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pelanggan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPelanggan }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Tagihan Belum Lunas (Jumlah)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahTagihanBelumLunas }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-file-earmark-excel-fill fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Tagihan Belum Lunas (Nominal)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalNominalBelumLunas, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-wallet2 fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pembayaran Bulan Ini (Nominal)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalNominalBulanIni, 0, ',', '.') }}</div>
                            <small class="text-muted">{{ $jumlahPembayaranBulanIni }} transaksi</small>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cash-coin fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-bar-chart-line-fill"></i> Tren Pemakaian Air (6 Bulan Terakhir)</h6>
                </div>
                <div class="card-body">
                    @if($chartData->isNotEmpty())
                    <canvas id="usageChart"></canvas>
                    @else
                    <p class="text-center text-muted">Data pemakaian tidak cukup untuk menampilkan grafik.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-list-stars"></i> Tagihan Terbaru Belum Lunas</h6>
                </div>
                <div class="card-body p-0">
                    @if($tagihanTerbaruBelumLunas->isEmpty())
                        <p class="text-center p-3 text-muted">Tidak ada tagihan belum lunas.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($tagihanTerbaruBelumLunas as $tagihan)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('pelanggan.show', $tagihan->pelanggan->id) }}">{{ $tagihan->pelanggan->nama_pelanggan }}</a>
                                    <small class="d-block text-muted">
                                        {{ \Carbon\Carbon::parse($tagihan->bulan_tagihan . '-01')->isoFormat('MMMM YYYY') }} - Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}
                                    </small>
                                </div>
                                <a href="{{ route('tagihan.bayar', $tagihan->id) }}" class="btn btn-sm btn-outline-success">Bayar</a>
                            </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-receipt-cutoff"></i> Pembayaran Terakhir</h6>
                </div>
                 <div class="card-body p-0">
                    @if($pembayaranTerakhir->isEmpty())
                        <p class="text-center p-3 text-muted">Belum ada riwayat pembayaran.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($pembayaranTerakhir as $pembayaran)
                            <li class="list-group-item">
                                <a href="{{ route('pelanggan.show', $pembayaran->tagihan->pelanggan->id) }}#tagihan-{{$pembayaran->tagihan_id}}">{{ $pembayaran->tagihan->pelanggan->nama_pelanggan }}</a>
                                <small class="d-block text-muted">
                                    Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }} - {{ \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->isoFormat('DD MMM YYYY') }}
                                    (Tagihan {{ \Carbon\Carbon::parse($pembayaran->tagihan->bulan_tagihan . '-01')->isoFormat('MMMM YYYY') }})
                                </small>
                            </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('usageChart');
    if (ctx) {
        const usageChart = new Chart(ctx, {
            type: 'line', // atau 'bar'
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Total Volume Pemakaian (m³)',
                    data: @json($chartData),
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Volume (m³)'
                        }
                    },
                    x: {
                         title: {
                            display: true,
                            text: 'Bulan'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y + ' m³';
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
<style>
    .card .border-left-primary { border-left: .25rem solid #007bff!important; }
    .card .border-left-success { border-left: .25rem solid #198754!important; }
    .card .border-left-info { border-left: .25rem solid #0dcaf0!important; }
    .card .border-left-warning { border-left: .25rem solid #ffc107!important; }
    .card .border-left-danger { border-left: .25rem solid #dc3545!important; }
    .text-gray-300 { color: #dddfeb!important; }
    .text-gray-800 { color: #5a5c69!important; }
    .text-xs { font-size: .8rem; }
    .shadow { box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15)!important; }
    .no-gutters { margin-right: 0; margin-left: 0; }
    .no-gutters > .col, .no-gutters > [class*="col-"] { padding-right: 0; padding-left: 0; }
</style>
@endsection