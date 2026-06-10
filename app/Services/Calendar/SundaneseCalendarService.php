<?php

namespace App\Services\Calendar;

use Carbon\Carbon;

class SundaneseCalendarService
{
    public function make(Carbon $date): array
    {
        return [
            'sundanese_day_name' => null,
            'sundanese_day_name_common' => null,
            'sundanese_market_day' => null,
            'sundanese_day' => null,
            'sundanese_month' => null,
            'sundanese_month_name' => null,
            'sundanese_year' => null,
            'sundanese_designation' => 'S',
            'sundanese_source' => null,
            'sundanese_is_verified' => false,
        ];
    }
}
