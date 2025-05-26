<?php

namespace App\Exports;

use App\Models\Tagihan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TagihanBulanExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected string $bulanTahun; // Format YYYY-MM

    public function __construct(string $bulanTahun)
    {
        $this->bulanTahun = $bulanTahun;
    }

    public function query()
    {
        return Tagihan::query()
            ->with(['pelanggan', 'pemakaianAir', 'pembayaran']) // Eager load relasi
            ->where('bulan_tagihan', $this->bulanTahun)
            ->whereHas('pelanggan') 
            ->whereHas('pemakaianAir') 
            ->orderBy('pelanggan_id'); 
    }

    public function headings(): array
    {
        return [
            'ID Pelanggan Kustom',
            'Nama Pelanggan',
            'Bulan Tagihan',
            'Meter Awal (m³)',
            'Meter Akhir (m³)',
            'Volume Pemakaian (m³)',
            'Tarif Pemakaian (Rp)', // (Volume x Tarif Satuan)
            'Pajak (Rp)',
            'Total Tagihan (Rp)',
            'Status Pembayaran',
            'Tanggal Bayar',
        ];
    }

    /**
    * @var Tagihan $tagihan
    */
    public function map($tagihan): array
    {
        // Asumsi tarif satuan adalah 1500, jika fleksibel, ambil dari config/database
        $tarifSatuan = 1500; 
        $subtotalPemakaian = ($tagihan->pemakaianAir->volume_pemakaian ?? 0) * $tarifSatuan;

        return [
            $tagihan->pelanggan->id_pelanggan ?? '-',
            $tagihan->pelanggan->nama_pelanggan ?? '-',
            $tagihan->bulan_tagihan,
            $tagihan->pemakaianAir->meter_awal ?? 0,
            $tagihan->pemakaianAir->meter_akhir ?? 0,
            $tagihan->pemakaianAir->volume_pemakaian ?? 0,
            $subtotalPemakaian, // Subtotal pemakaian
            $tagihan->jumlah_pajak ?? 0,
            $tagihan->jumlah_tagihan ?? 0, // Ini adalah total akhir
            $tagihan->status_pembayaran,
            $tagihan->pembayaran ? ($tagihan->pembayaran->tanggal_pembayaran ? \Carbon\Carbon::parse($tagihan->pembayaran->tanggal_pembayaran)->isoFormat('DD MMMM YYYY') : '-') : '-',
        ];
    }
}