<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'creditAmount',
        'description',
        'pos',
        'ma_giao_dich',
    ];
}
