<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoaiXe extends Model
{
    protected $table = 'loai_xes';
    protected $fillable = [
        'ten_loai_xe',
        'trang_thai',
        'tien_thu_giu_xe',
    ];
}
