<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * Имя таблицы
     */
    protected $table = 'orders';

    /**
     * Поля, которые можно заполнять
     */
    protected $fillable = [
        'data',
        'order_date',
        'external_id'
    ];

    /**
     * Поля в формате JSON
     */
    protected $casts = [
        'data' => 'array',
        'order_date' => 'date'
    ];
}
