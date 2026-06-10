<?php

namespace App\Services\Calendar;

use App\Models\CalendarDate;
use Carbon\Carbon;

class CalendarAvailabilityService
{
    public function __construct(
        private readonly CalendarGeneratorService $generator
    ) {}

    public function ensureMonthAvailable(int $year, int $month): array
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $expectedDays = $start->daysInMonth;

        $existingDays = CalendarDate::query()
            ->whereBetween('gregorian_date', [
                $start->toDateString(),
                $end->toDateString(),
            ])
            ->count();

        if ($existingDays >= $expectedDays) {
            return [
                'status' => 'available',
                'generated' => false,
            ];
        }

        $result = $this->generator->generateMonth(
            year: $year,
            month: $month,
            force: false
        );

        return [
            'status' => 'generated',
            'generated' => true,
            'result' => $result,
        ];
    }
}
