<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietBaiXe extends Model
{
    protected $table = 'chi_tiet_bai_xes';
    protected $fillable = [
        'id_bai_xe',
        'thu_tu',
        'loai_xe',
        'trang_thai',
    ];
}
