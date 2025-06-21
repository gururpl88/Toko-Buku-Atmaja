<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $table = 'pembelian';
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_pembelian',
        'id_distributor',
        'id_buku',
        'jumlah_beli',
        'tanggal_beli',
        'id'
    ];
    
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'id_distributor', 'id');
    }
}
