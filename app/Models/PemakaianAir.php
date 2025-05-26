<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemakaianAir extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelanggan_id',
        'bulan',
        'meter_awal',
        'meter_akhir',
        'volume_pemakaian',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function tagihan()
    {
        return $this->hasOne(Tagihan::class);
    }
}