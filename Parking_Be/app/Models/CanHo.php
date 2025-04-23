<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CanHo extends Model
{
    protected $table = 'can_hos';
    protected $fillable = [
        'tang',
        'so_can_ho',
        'chu_ho',
    ];
}
