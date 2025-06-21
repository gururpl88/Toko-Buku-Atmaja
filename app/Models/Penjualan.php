<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';
    public $timestamps = false;
    protected $primaryKey = 'id_penjualan';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_penjualan',
        'id_kasir',
        'tanggal_penjualan'
    ];
    public function kasir() {
        return $this->belongsTo(Kasir::class, 'id_kasir', 'id_kasir');
    }
    
    public function detailPenjualan() {
        return $this->hasMany(DetailPenjualan::class, 'id_penjualan', 'id_penjualan');
    }
}
