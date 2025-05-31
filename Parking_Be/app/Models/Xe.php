<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Xe extends Model
{
    protected $table = 'xes';
    protected $fillable = [
        'id_cu_dan',
        'bien_so_xe',
        'id_loai_xe',
        'trang_thai_duyet',
        'is_con_han'
    ];
}
