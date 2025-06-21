<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';
    protected $primaryKey = 'id_buku';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_buku',
        'judul_buku',
        'nomor_isbn',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'stok',
        'harga_pokok',
        'harga_jual',
        'diskon'
    ];
    public function pembelian() {
        return $this->hasMany(Pembelian::class, 'id_buku', 'id_buku');
    }
    
    public function detailPenjualan() {
        return $this->hasMany(DetailPenjualan::class, 'id_buku', 'id_buku');
    }
}
