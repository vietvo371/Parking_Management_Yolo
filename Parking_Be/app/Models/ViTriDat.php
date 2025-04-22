<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViTriDat extends Model
{
    protected $table = 'vi_tri_dats';
    protected $fillable = [
        'vi_tri_dat',
        'trang_thai',
    ];
}
