<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    /**
     * Имя таблицы
     */
    protected $table = 'stocks';

    /**
     * Поля, которые можно заполнять
     */
    protected $fillable = [
        'data',
        'stock_date',
        'external_id'
    ];

    /**
     * Поля в формате JSON
     */
    protected $casts = [
        'data' => 'array',
        'stock_date' => 'date'
    ];
}
