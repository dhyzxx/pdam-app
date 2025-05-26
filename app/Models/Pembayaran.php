<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'tagihan_id',
        'pelanggan_id',
        'tanggal_pembayaran',
        'jumlah_bayar',
    ];

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}