<?php

namespace App\Http\Controllers;

use App\Models\PemakaianAir;
use App\Models\Pelanggan;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PemakaianAirController extends Controller
{
    public function create(Pelanggan $pelanggan)
    {
        return view('pemakaian_air.create', compact('pelanggan'));
    }

    public function store(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'bulan' => 'required|date_format:Y-m',
            'meter_awal' => 'required|numeric|min:0',
            'meter_akhir' => 'required|numeric|min:' . $request->input('meter_awal', 0),
        ]);

        $existingUsage = PemakaianAir::where('pelanggan_id', $pelanggan->id)
                                     ->where('bulan', $request->bulan)
                                     ->first();

        if ($existingUsage) {
            return redirect()->back()->withInput()->withErrors(['bulan' => 'Data pemakaian untuk bulan ini sudah ada.']);
        }

        $volume_pemakaian = $request->meter_akhir - $request->meter_awal;

        $pemakaian = PemakaianAir::create([
            'pelanggan_id' => $pelanggan->id,
            'bulan' => $request->bulan,
            'meter_awal' => $request->meter_awal,
            'meter_akhir' => $request->meter_akhir,
            'volume_pemakaian' => $volume_pemakaian,
        ]);

        // Buat tagihan otomatis
        $tarif_per_volume = 1500; // Rp. 1500 (Sebaiknya ini juga dari pengaturan)
        $pajak_tetap = 2000;      // Pajak tetap bulanan

        $subtotal_pemakaian = $volume_pemakaian * $tarif_per_volume;
        $total_tagihan_akhir = $subtotal_pemakaian + $pajak_tetap;

        Tagihan::create([
            'pelanggan_id' => $pelanggan->id,
            'pemakaian_air_id' => $pemakaian->id,
            'bulan_tagihan' => $request->bulan,
            'jumlah_tagihan' => $total_tagihan_akhir, // Total akhir
            'jumlah_pajak' => $pajak_tetap,         // Simpan jumlah pajak
            'status_pembayaran' => 'Belum Lunas',
        ]);

        return redirect()->route('pelanggan.show', $pelanggan->id)
                         ->with('success', 'Data pemakaian air dan tagihan (termasuk pajak Rp '.number_format($pajak_tetap,0,',','.').') berhasil ditambahkan.');
    }

    public function edit(PemakaianAir $pemakaianAir)
    {
        $pelanggan = $pemakaianAir->pelanggan;
        return view('pemakaian_air.edit', compact('pemakaianAir', 'pelanggan'));
    }

    public function update(Request $request, PemakaianAir $pemakaianAir)
    {
        $request->validate([
            'bulan' => 'required|date_format:Y-m',
            'meter_awal' => 'required|numeric|min:0',
            'meter_akhir' => 'required|numeric|min:' . $request->input('meter_awal', 0),
        ]);

        $existingUsage = PemakaianAir::where('pelanggan_id', $pemakaianAir->pelanggan_id)
                                     ->where('bulan', $request->bulan)
                                     ->where('id', '!=', $pemakaianAir->id)
                                     ->first();

        if ($existingUsage) {
            return redirect()->back()->withInput()->withErrors(['bulan' => 'Data pemakaian untuk bulan ini sudah ada.']);
        }

        $volume_pemakaian = $request->meter_akhir - $request->meter_awal;

        $pemakaianAir->update([
            'bulan' => $request->bulan,
            'meter_awal' => $request->meter_awal,
            'meter_akhir' => $request->meter_akhir,
            'volume_pemakaian' => $volume_pemakaian,
        ]);

        // Update tagihan terkait
        if ($pemakaianAir->tagihan) {
            $tarif_per_volume = 1500; // Sebaiknya ini juga dari pengaturan
            $pajak_tetap = 2000;      // Pajak tetap bulanan

            $subtotal_pemakaian = $volume_pemakaian * $tarif_per_volume;
            $total_tagihan_akhir = $subtotal_pemakaian + $pajak_tetap;

            $pemakaianAir->tagihan->update([
                'bulan_tagihan' => $request->bulan,
                'jumlah_tagihan' => $total_tagihan_akhir,
                'jumlah_pajak' => $pajak_tetap,
            ]);
        } else {
            // Jika tagihan belum ada (kasus jarang terjadi jika alur normal), buat baru
            $tarif_per_volume = 1500;
            $pajak_tetap = 2000;
            $subtotal_pemakaian = $volume_pemakaian * $tarif_per_volume;
            $total_tagihan_akhir = $subtotal_pemakaian + $pajak_tetap;

            Tagihan::create([
                'pelanggan_id' => $pemakaianAir->pelanggan_id,
                'pemakaian_air_id' => $pemakaianAir->id,
                'bulan_tagihan' => $request->bulan,
                'jumlah_tagihan' => $total_tagihan_akhir,
                'jumlah_pajak' => $pajak_tetap,
                'status_pembayaran' => 'Belum Lunas',
            ]);
        }

        return redirect()->route('pelanggan.show', $pemakaianAir->pelanggan_id)
                         ->with('success', 'Data pemakaian air dan tagihan (termasuk pajak Rp '.number_format($pajak_tetap,0,',','.').') berhasil diperbarui.');
    }

    public function destroy(PemakaianAir $pemakaianAir)
    {
        // Hapus juga tagihan terkait jika belum lunas
        if ($pemakaianAir->tagihan && $pemakaianAir->tagihan->status_pembayaran == 'Belum Lunas') {
            $pemakaianAir->tagihan->delete();
        } elseif ($pemakaianAir->tagihan && $pemakaianAir->tagihan->status_pembayaran == 'Lunas') {
            return redirect()->route('pelanggan.show', $pemakaianAir->pelanggan_id)
                             ->with('error', 'Tidak dapat menghapus pemakaian air yang tagihannya sudah lunas.');
        }

        $pelanggan_id = $pemakaianAir->pelanggan_id;
        $pemakaianAir->delete();

        return redirect()->route('pelanggan.show', $pelanggan_id)
                         ->with('success', 'Data pemakaian air berhasil dihapus.');
    }
}