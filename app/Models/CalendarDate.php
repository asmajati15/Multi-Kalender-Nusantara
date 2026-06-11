<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarDate extends Model
{
    protected $primaryKey = 'gregorian_date';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'gregorian_date',

        'gregorian_day_name',
        'gregorian_day',
        'gregorian_month',
        'gregorian_month_name',
        'gregorian_year',

        'hijri_day_name',
        'hijri_day',
        'hijri_month',
        'hijri_month_name',
        'hijri_year',
        'hijri_designation',
        'hijri_source',
        'hijri_is_verified',

        'javanese_day_name',
        'javanese_market_day',
        'javanese_day',
        'javanese_month',
        'javanese_month_name',
        'javanese_year',
        'javanese_designation',
        'javanese_source',
        'javanese_is_verified',

        'sundanese_day_name',
        'sundanese_day_name_common',
        'sundanese_market_day',
        'sundanese_day',
        'sundanese_month',
        'sundanese_month_name',
        'sundanese_year',
        'sundanese_designation',
        'sundanese_source',
        'sundanese_is_verified',

        'is_generated',
        'is_complete',
        'notes',
    ];

    protected $casts = [
        'hijri_is_verified' => 'boolean',
        'javanese_is_verified' => 'boolean',
        'sundanese_is_verified' => 'boolean',

        'is_generated' => 'boolean',
        'is_complete' => 'boolean',
    ];
}
