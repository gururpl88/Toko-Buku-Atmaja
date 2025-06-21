<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kasir extends Model
{
    protected $table = 'kasir';
    public $timestamps = false;
    protected $primaryKey = 'id_kasir';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_kasir',
        'nama_kasir',
        'alamat_kasir',
        'telp',
        'status_kasir',
        'username',
        'password',
        'akses'
    ];

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'id_penjualan', 'id');
    }
}
