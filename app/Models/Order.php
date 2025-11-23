<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * Jadval nomi
     */
    protected $table = 'orders';

    /**
     * To'ldirish mumkin bo'lgan maydonlar
     */
    protected $fillable = [
        'data',
        'order_date',
        'external_id'
    ];

    /**
     * JSON formatdagi maydonlar
     */
    protected $casts = [
        'data' => 'array',
        'order_date' => 'date'
    ];
}
