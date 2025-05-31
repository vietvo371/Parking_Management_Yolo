<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CamGiamSoat extends Model
{
    protected $table = 'cam_giam_soats';
    protected $fillable = [
        'id_vi_tri',
        'id_bai_xe',
        'trang_thai'
    ];
}
