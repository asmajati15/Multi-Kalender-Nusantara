<?php

namespace App\Services\Calendar;

use Carbon\Carbon;

class JavaneseCalendarService
{
    public function make(Carbon $date): array
    {
        return [
            'javanese_day_name' => null,
            'javanese_market_day' => null,
            'javanese_day' => null,
            'javanese_month' => null,
            'javanese_month_name' => null,
            'javanese_year' => null,
            'javanese_designation' => 'J',
            'javanese_source' => null,
            'javanese_is_verified' => false,
        ];
    }
}
