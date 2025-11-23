<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    /**
     * Имя таблицы
     */
    protected $table = 'sales';

    /**
     * Поля, которые можно заполнять
     */
    protected $fillable = [
        'data',
        'sale_date',
        'external_id'
    ];

    /**
     * Поля в формате JSON
     */
    protected $casts = [
        'data' => 'array',
        'sale_date' => 'date'
    ];
}
