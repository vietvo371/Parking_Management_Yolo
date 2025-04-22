<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiaoDich extends Model
{
    protected $table = 'giao_diches';

    protected $fillable = [
        'so_tien_giao_dich',
        'ma_giao_dich',
        'id_xe',
        'phuong_thuc_thanh_toan',
        'trang_thai_giao_dich',
        'ngay_het_han'
    ];
}
