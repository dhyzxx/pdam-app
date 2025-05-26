<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelanggan_id',
        'pemakaian_air_id',
        'bulan_tagihan',
        'jumlah_tagihan', 
        'jumlah_pajak',   
        'status_pembayaran',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function pemakaianAir()
    {
        return $this->belongsTo(PemakaianAir::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }
}