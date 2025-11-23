<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    /**
     * Jadval nomi
     */
    protected $table = 'incomes';

    /**
     * To'ldirish mumkin bo'lgan maydonlar
     */
    protected $fillable = [
        'data',
        'income_date',
        'external_id'
    ];

    /**
     * JSON formatdagi maydonlar
     */
    protected $casts = [
        'data' => 'array',
        'income_date' => 'date'
    ];
}
