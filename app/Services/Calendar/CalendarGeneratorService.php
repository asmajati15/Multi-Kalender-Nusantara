<?php

namespace App\Services\Calendar;

use App\Models\CalendarDate;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class CalendarGeneratorService
{
    public function __construct(
        private readonly GregorianCalendarService $gregorianService,
        private readonly HijriCalendarImportService $hijriService,
        private readonly JavaneseCalendarService $javaneseService,
        private readonly SundaneseCalendarService $sundaneseService,
    ) {}

    public function generateMonth(int $year, int $month, bool $force = false): array
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $stats = [
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'failed' => 0,
        ];

        $hijriByGregorianDate = $this->hijriService->getMonth($year, $month);

        foreach (CarbonPeriod::create($start, $end) as $date) {
            try {
                $gregorianDate = $date->toDateString();

                $existing = CalendarDate::query()
                    ->where('gregorian_date', $gregorianDate)
                    ->first();

                if ($existing && $existing->is_complete && ! $force) {
                    $stats['skipped']++;
                    continue;
                }

                $payload = array_merge(
                    $this->gregorianService->make($date),
                    $hijriByGregorianDate[$gregorianDate] ?? [],
                    $this->javaneseService->make($date),
                    $this->sundaneseService->make($date),
                    [
                        'is_generated' => true,
                        'is_complete' => $this->isPayloadComplete($hijriByGregorianDate[$gregorianDate] ?? []),
                    ]
                );

                DB::transaction(function () use ($gregorianDate, $payload, $existing, &$stats) {
                    CalendarDate::query()->updateOrCreate(
                        ['gregorian_date' => $gregorianDate],
                        $payload
                    );

                    if ($existing) {
                        $stats['updated']++;
                    } else {
                        $stats['created']++;
                    }
                });
            } catch (\Throwable $e) {
                report($e);
                $stats['failed']++;
            }
        }

        return $stats;
    }

    private function isPayloadComplete(array $hijri): bool
    {
        // Pada tahap awal, complete berarti data Masehi + Hijriyah sudah tersedia.
        // Jawa dan Sunda belum diwajibkan complete karena algoritmanya belum dibuat.
        return filled($hijri['hijri_day'] ?? null)
            && filled($hijri['hijri_month'] ?? null)
            && filled($hijri['hijri_year'] ?? null);
    }
}
