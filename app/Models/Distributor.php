<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    protected $table = 'distributor';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = [
        'kode',
        'nama_distributor',
        'alamat',
        'telp_distributor',
        'id'
    ];

    public function pembelian()
    {
        return $this->hasMany(Pembelian::class, 'id_distributor', 'id');
    }
}
