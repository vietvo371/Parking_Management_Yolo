<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaoCaoKhachVangLai extends Model
{
    protected $table = 'bao_cao_khach_vang_lais';
    protected $fillable = [
        'id_admin',
        'ho_va_ten',
        'so_dien_thoai',
        'thoi_gian_vao',
        'thoi_gian_ra',
        'tien_thanh_toan',
        'is_thanh_toan',
        'id_vi_tri_trong_bai'
    ];
}
