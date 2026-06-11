<?php

namespace App\Services\Calendar;

use Carbon\Carbon;

class JavaneseCalendarService
{
    private const EPOCH_DATE = '1633-07-08';
    private const EPOCH_YEAR = 1555;

    // Pasaran cycle: 0=Legi, 1=Pahing, 2=Pon, 3=Wage, 4=Kliwon
    // 8 Juli 1633 adalah hari Jumat Legi
    private const PASARAN = ['Legi', 'Pahing', 'Pon', 'Wage', 'Kliwon'];
    private const DAYS = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    private const MONTHS = [
        1 => 'Sura', 2 => 'Sapar', 3 => 'Mulud', 4 => 'Bakda Mulud',
        5 => 'Jumadil Awal', 6 => 'Jumadil Akhir', 7 => 'Rejeb', 8 => 'Ruwah',
        9 => 'Pasa', 10 => 'Sawal', 11 => 'Dulkangidah', 12 => 'Besar'
    ];

    public function make(Carbon $date): array
    {
        $epoch = Carbon::createFromFormat('Y-m-d', self::EPOCH_DATE)->startOfDay();
        $target = $date->copy()->startOfDay();

        if ($target->lessThan($epoch)) {
            return $this->emptyResult();
        }

        $diffDays = (int) $epoch->diffInDays($target);

        // Pasaran
        $pasaranIndex = $diffDays % 5;
        $pasaran = self::PASARAN[$pasaranIndex];

        // Hari
        $dayNameIndex = $target->dayOfWeek; // 0 (Minggu) - 6 (Sabtu)
        $dayName = self::DAYS[$dayNameIndex];

        // Tahun, Bulan, Tanggal
        $cycles = intdiv($diffDays, 2835);
        $remainderDays = $diffDays % 2835;

        // Tahun 1,3,4,6,7 = 354 hari. Sisanya (2,5,8) = 355 hari.
        $daysInYears = [
            1 => 354, 2 => 355, 3 => 354, 4 => 354,
            5 => 355, 6 => 354, 7 => 354, 8 => 355,
        ];

        $yearInCycle = 1;
        foreach ($daysInYears as $y => $days) {
            if ($remainderDays >= $days) {
                $remainderDays -= $days;
                $yearInCycle++;
            } else {
                break;
            }
        }

        $javaneseYear = self::EPOCH_YEAR + ($cycles * 8) + ($yearInCycle - 1);

        $isLeapYear = in_array($yearInCycle, [2, 5, 8]);
        $monthsDays = [
            1 => 30, 2 => 29, 3 => 30, 4 => 29, 5 => 30, 6 => 29,
            7 => 30, 8 => 29, 9 => 30, 10 => 29, 11 => 30,
            12 => $isLeapYear ? 30 : 29,
        ];

        $javaneseMonth = 1;
        foreach ($monthsDays as $m => $days) {
            if ($remainderDays >= $days) {
                $remainderDays -= $days;
                $javaneseMonth++;
            } else {
                break;
            }
        }

        $javaneseDay = $remainderDays + 1;

        return [
            'javanese_day_name' => $dayName,
            'javanese_market_day' => $pasaran,
            'javanese_day' => $javaneseDay,
            'javanese_month' => $javaneseMonth,
            'javanese_month_name' => self::MONTHS[$javaneseMonth],
            'javanese_year' => $javaneseYear,
            'javanese_designation' => 'J',
            'javanese_source' => 'Calculated (Algoritma Sultan Agung)',
            'javanese_is_verified' => true,
        ];
    }

    private function emptyResult(): array
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
