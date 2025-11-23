<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    /**
     * Jadval nomi
     */
    protected $table = 'stocks';

    /**
     * To'ldirish mumkin bo'lgan maydonlar
     */
    protected $fillable = [
        'data',
        'stock_date',
        'external_id'
    ];

    /**
     * JSON formatdagi maydonlar
     */
    protected $casts = [
        'data' => 'array',
        'stock_date' => 'date'
    ];
}
