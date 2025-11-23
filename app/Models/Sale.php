<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    /**
     * Jadval nomi
     */
    protected $table = 'sales';

    /**
     * To'ldirish mumkin bo'lgan maydonlar
     */
    protected $fillable = [
        'data',
        'sale_date',
        'external_id'
    ];

    /**
     * JSON formatdagi maydonlar
     */
    protected $casts = [
        'data' => 'array',
        'sale_date' => 'date'
    ];
}
