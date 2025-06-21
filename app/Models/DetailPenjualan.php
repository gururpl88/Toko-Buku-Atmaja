<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    protected $table = 'detail_penjualan';
    public $timestamps = false;
    protected $fillable = [
        'id_det_penjualan',
        'id_penjualan',
        'id_buku',
        'jumlah_jual'
    ];

    public function kasir() {
        return $this->belongsTo(Kasir::class, 'id_kasir', 'id_kasir');
    }
    
    public function detailPenjualan() {
        return $this->hasMany(DetailPenjualan::class, 'id_penjualan', 'id_penjualan');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }
}
