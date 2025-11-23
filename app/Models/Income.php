<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    /**
     * Имя таблицы
     */
    protected $table = 'incomes';

    /**
     * Поля, которые можно заполнять
     */
    protected $fillable = [
        'data',
        'income_date',
        'external_id'
    ];

    /**
     * Поля в формате JSON
     */
    protected $casts = [
        'data' => 'array',
        'income_date' => 'date'
    ];
}
