<?php

namespace App\Exports;

use App\Models\Pelanggan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping; // Tambahkan ini untuk kustomisasi data

class PelangganExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Ambil semua pelanggan, bisa diurutkan jika perlu
        return Pelanggan::orderBy('nama_pelanggan')->get();
    }

    public function headings(): array
    {
        return [
            'ID Pelanggan Sistem', // ID auto-increment dari tabel
            'ID Pelanggan Kustom', // Kolom id_pelanggan Anda
            'Nama Pelanggan',
            'Tanggal Terdaftar',
        ];
    }

    /**
    * @param Pelanggan $pelanggan
    */
    public function map($pelanggan): array
    {
        // Memetakan data ke array untuk setiap baris di Excel
        return [
            $pelanggan->id,
            $pelanggan->id_pelanggan,
            $pelanggan->nama_pelanggan,
            $pelanggan->created_at ? $pelanggan->created_at->isoFormat('DD MMMM YYYY, HH:mm') : '-',
        ];
    }
}