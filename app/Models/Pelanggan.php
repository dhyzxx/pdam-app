<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $fillable = ['id_pelanggan', 'nama_pelanggan'];

    public function pemakaianAir()
    {
        return $this->hasMany(PemakaianAir::class);
    }

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class);
    }
}