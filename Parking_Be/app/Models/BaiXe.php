<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaiXe extends Model
{
    protected $table = 'bai_xes';
    protected $fillable = [
        'ten_bai',
        'suc_chua_o_to',
        'suc_chua_khac',
        'trang_thai',
    ];
}
