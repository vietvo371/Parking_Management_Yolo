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

    const TRANG_THAI_GIAO_DICH_CHUA_THANH_TOAN = 0;
    const TRANG_THAI_GIAO_DICH_DA_THANH_TOAN = 1;


    const PHUONG_THUC_THANH_TOAN_TIEN_MAT = 1;
    const PHUONG_THUC_THANH_TOAN_TIEN_CHUYEN_KHOAN = 2;


}
