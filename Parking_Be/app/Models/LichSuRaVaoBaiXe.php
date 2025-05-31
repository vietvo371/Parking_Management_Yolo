<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichSuRaVaoBaiXe extends Model
{
    protected $table = 'lich_su_ra_vao_bai_xes';
    protected $fillable = [
        'id_xe_cu_dan',
        'id_camera_quet',
        'thoi_gian_vao',
        'thoi_gian_ra',
        'id_vi_tri_trong_bai'
    ];
}
