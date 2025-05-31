<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaoCaoSuCo extends Model
{
    protected $table = 'bao_cao_su_cos';
    protected $fillable = [
        'id_cu_dan_bao_cao',
        'noi_dung_bao_cao',
        'trang_thai_xu_ly',
        'ngay_tao',
        'id_admin_xu_ly'
    ];

    const TRANG_THAI_CHUA_XU_LY = 0;
    const TRANG_THAI_DA_XU_LY = 1;

}
