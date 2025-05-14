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
        'is_cu_dan',
        'is_khach_hang'
    ];

    const TRANG_THAI_TRONG = 0;
    const TRANG_THAI_DA_DAT_TRUOC = 1;
    const TRANG_THAI_DA_CO_XE = 2;
}
