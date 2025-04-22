<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuDan extends Model
{
    protected $table = 'cu_dans';
    protected $fillable = [
        'ho_va_ten',
        'email',
        'password',
        'so_dien_thoai',
        'so_cccd',
        'id_can_ho',
        'so_du',
        'phe_duyet',
        'trang_thai'
    ];
}
