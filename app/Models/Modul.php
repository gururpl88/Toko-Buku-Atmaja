<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    protected $table = 'moduls';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'icon',
        'route',
    ];
}
