<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminThongBao extends Model
{
    protected $table = 'admin_thong_baos';
    protected $fillable = [
        'id_admin',
        'id_cu_dan',
        'noi_dung_thong_bao',
        'ngay_tao',
        'trang_thai'
    ];
}
