<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class CuDan extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
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
