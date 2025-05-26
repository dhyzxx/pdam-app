<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPelanggan = Pelanggan::count();

        $tagihanBelumLunas = Tagihan::where('status_pembayaran', 'Belum Lunas');
        $jumlahTagihanBelumLunas = $tagihanBelumLunas->count();
        $totalNominalBelumLunas = $tagihanBelumLunas->sum('jumlah_tagihan');

        $pembayaranBulanIni = Pembayaran::whereYear('tanggal_pembayaran', Carbon::now()->year)
                                       ->whereMonth('tanggal_pembayaran', Carbon::now()->month);
        $jumlahPembayaranBulanIni = $pembayaranBulanIni->count();
        $totalNominalBulanIni = $pembayaranBulanIni->sum('jumlah_bayar');

        $tagihanTerbaruBelumLunas = Tagihan::where('status_pembayaran', 'Belum Lunas')
                                           ->with('pelanggan')
                                           ->orderBy('created_at', 'desc')
                                           ->limit(5)
                                           ->get();

        $pembayaranTerakhir = Pembayaran::with('tagihan.pelanggan')
                                        ->orderBy('tanggal_pembayaran', 'desc')
                                        ->limit(5)
                                        ->get();

        // Data untuk grafik sederhana (misalnya, pemakaian air 6 bulan terakhir)
        // Ini adalah contoh sederhana, Anda mungkin perlu menyesuaikan query berdasarkan struktur data pemakaian_airs
        $usageData = DB::table('pemakaian_airs')
            ->select(
                DB::raw("DATE_FORMAT(CONCAT(bulan, '-01'), '%Y-%m') as month_year"), // Pastikan 'bulan' adalah YYYY-MM
                DB::raw('SUM(volume_pemakaian) as total_volume')
            )
            ->where('bulan', '>=', Carbon::now()->subMonths(5)->format('Y-m'))
            ->groupBy('month_year')
            ->orderBy('month_year', 'asc')
            ->get();

        $chartLabels = $usageData->pluck('month_year')->map(function ($date) {
            return Carbon::parse($date)->isoFormat('MMM YYYY');
        });
        $chartData = $usageData->pluck('total_volume');


        return view('dashboard.index', compact(
            'totalPelanggan',
            'jumlahTagihanBelumLunas',
            'totalNominalBelumLunas',
            'jumlahPembayaranBulanIni',
            'totalNominalBulanIni',
            'tagihanTerbaruBelumLunas',
            'pembayaranTerakhir',
            'chartLabels',
            'chartData'
        ));
    }
}