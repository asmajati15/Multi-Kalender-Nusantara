<?php

namespace App\Services\Calendar;

use Carbon\Carbon;

class GregorianCalendarService
{
    public function make(Carbon $date): array
    {
        return [
            'gregorian_date' => $date->toDateString(),
            'gregorian_day_name' => $this->getIndonesianDayName($date),
            'gregorian_day' => (int) $date->format('d'),
            'gregorian_month' => (int) $date->format('m'),
            'gregorian_month_name' => $this->getIndonesianMonthName((int) $date->format('m')),
            'gregorian_year' => (int) $date->format('Y'),
        ];
    }

    private function getIndonesianDayName(Carbon $date): string
    {
        return [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ][$date->format('l')];
    }

    private function getIndonesianMonthName(int $month): string
    {
        return [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ][$month];
    }
}
