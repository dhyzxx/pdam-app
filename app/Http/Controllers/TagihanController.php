<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TagihanBulanExport;


class TagihanController extends Controller
{
    public function index(Request $request)
    {
        $query = Tagihan::with('pelanggan', 'pemakaianAir')->orderBy('created_at', 'desc');

        if ($request->has('search_pelanggan') && $request->search_pelanggan != '') {
            $query->whereHas('pelanggan', function ($q) use ($request) {
                $q->where('nama_pelanggan', 'like', "%{$request->search_pelanggan}%")
                  ->orWhere('id_pelanggan', 'like', "%{$request->search_pelanggan}%");
            });
        }

        if ($request->has('filter_bulan') && $request->filter_bulan != '') {
            $query->where('bulan_tagihan', $request->filter_bulan);
        }

        if ($request->has('filter_status') && $request->filter_status != '') {
            $query->where('status_pembayaran', $request->filter_status);
        }

        $tagihans = $query->paginate(10);
        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get(); // Untuk filter

        return view('tagihan.index', compact('tagihans', 'pelanggans'));
    }

    public function bayar(Tagihan $tagihan)
    {
        if ($tagihan->status_pembayaran == 'Lunas') {
            return redirect()->route('tagihan.index')->with('error', 'Tagihan ini sudah lunas.');
        }
        return view('tagihan.bayar', compact('tagihan'));
    }

    public function prosesBayar(Request $request, Tagihan $tagihan)
    {
        if ($tagihan->status_pembayaran == 'Lunas') {
            return redirect()->route('tagihan.index')->with('error', 'Tagihan ini sudah lunas.');
        }

        $request->validate([
            'tanggal_pembayaran' => 'required|date',
            'jumlah_bayar' => 'required|numeric|min:' . $tagihan->jumlah_tagihan, // Memastikan bayar minimal sama dengan tagihan
        ]);

        Pembayaran::create([
            'tagihan_id' => $tagihan->id,
            'pelanggan_id' => $tagihan->pelanggan_id,
            'tanggal_pembayaran' => $request->tanggal_pembayaran,
            'jumlah_bayar' => $request->jumlah_bayar, // Bisa dibuat agar sama dengan jumlah tagihan
        ]);

        $tagihan->update(['status_pembayaran' => 'Lunas']);

        return redirect()->route('tagihan.index')
                         ->with('success', 'Pembayaran tagihan berhasil.');
    }

    public function riwayatPembayaran(Request $request)
    {
        $query = Pembayaran::with('tagihan.pelanggan', 'tagihan.pemakaianAir')->orderBy('tanggal_pembayaran', 'desc');

        if ($request->has('search_pelanggan_riwayat') && $request->search_pelanggan_riwayat != '') {
            $query->whereHas('pelanggan', function ($q) use ($request) {
                $q->where('nama_pelanggan', 'like', "%{$request->search_pelanggan_riwayat}%")
                  ->orWhere('id_pelanggan', 'like', "%{$request->search_pelanggan_riwayat}%");
            });
        }

        if ($request->has('filter_bulan_riwayat') && $request->filter_bulan_riwayat != '') {
            $query->whereHas('tagihan', function ($q) use ($request) {
                $q->where('bulan_tagihan', $request->filter_bulan_riwayat);
            });
        }


        $pembayarans = $query->paginate(10);
        return view('tagihan.riwayat', compact('pembayarans'));
    }
    public function cetakBuktiPembayaranPdf(Pembayaran $pembayaran)
    {
        $pembayaran->load('tagihan.pelanggan', 'tagihan.pemakaianAir');

        if (!$pembayaran->tagihan || !$pembayaran->tagihan->pemakaianAir) {
            return redirect()->back()->with('error', 'Data tagihan atau pemakaian air terkait pembayaran ini tidak lengkap.');
        }

        $pdf = PDF::loadView('pdf.bukti_pembayaran_pdf', compact('pembayaran'));
        // return $pdf->download('bukti-bayar-' . $pembayaran->tagihan->pelanggan->id_pelanggan . '-' . $pembayaran->tagihan->bulan_tagihan . '.pdf');
        return $pdf->stream('bukti-bayar-' . $pembayaran->tagihan->pelanggan->id_pelanggan . '-' . $pembayaran->tagihan->bulan_tagihan . '.pdf');
    }
    public function cetakTagihanPdf(Tagihan $tagihan)
    {
        $tagihan->load('pelanggan', 'pemakaianAir', 'pembayaran');

        if (!$tagihan->pemakaianAir) {
            return redirect()->back()->with('error', 'Data pemakaian air untuk tagihan ini tidak ditemukan.');
        }

        $pdf = PDF::loadView('pdf.tagihan_pdf', compact('tagihan'));
        // return $pdf->download('tagihan-' . $tagihan->pelanggan->id_pelanggan . '-' . $tagihan->bulan_tagihan . '.pdf');
        return $pdf->stream('tagihan-' . $tagihan->pelanggan->id_pelanggan . '-' . $tagihan->bulan_tagihan . '.pdf'); // Untuk preview di browser
    }
    public function formExportTagihanBulan()
    {
        return view('tagihan.form_export_bulan');
    }

    public function prosesExportTagihanBulan(Request $request)
    {
        $request->validate([
            'bulan_export' => 'required|date_format:Y-m', // Format YYYY-MM
        ]);

        $bulanTahun = $request->input('bulan_export');
        // Buat nama file yang lebih deskriptif dengan bulan dan tahun
        $periode = \Carbon\Carbon::createFromFormat('Y-m', $bulanTahun)->isoFormat('MMMM_YYYY');
        $namaFile = 'tagihan_pdam_' . $periode . '.xlsx';

        return Excel::download(new TagihanBulanExport($bulanTahun), $namaFile);
    }
}