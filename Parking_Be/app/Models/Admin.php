<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admins';
    protected $fillable = [
        'ho_va_ten',
        'email',
        'password',
        'id_chuc_vu',
        'so_dien_thoai',
    ];
}
