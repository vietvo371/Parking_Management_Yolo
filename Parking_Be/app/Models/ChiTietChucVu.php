<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietChucVu extends Model
{
    protected $table = 'chi_tiet_chuc_vus';
    protected $fillable = [
        'id_chuc_vu',
        'id_chuc_nang',
        'tinh_trang',
    ];
}
