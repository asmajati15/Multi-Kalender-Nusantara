<?php

namespace App\Services\Calendar;

use App\Services\Calendar\Clients\AladhanHijriApiClient;
use Carbon\Carbon;

class HijriCalendarImportService
{
    public function __construct(
        private readonly AladhanHijriApiClient $client
    ) {}

    public function getMonth(int $year, int $month): array
    {
        $items = $this->client->getGregorianToHijriCalendar($year, $month);

        $mapped = [];

        foreach ($items as $item) {
            $gregorianDate = $this->normalizeGregorianDate($item);

            if (! $gregorianDate) {
                continue;
            }

            $mapped[$gregorianDate] = [
                'hijri_day_name' => $item['gregorian']['weekday']['en'] ?? null,
                'hijri_day' => isset($item['hijri']['day']) ? (int) $item['hijri']['day'] : null,
                'hijri_month' => isset($item['hijri']['month']['number']) ? (int) $item['hijri']['month']['number'] : null,
                'hijri_month_name' => $this->normalizeHijriMonthName($item['hijri']['month']['en'] ?? null),
                'hijri_year' => isset($item['hijri']['year']) ? (int) $item['hijri']['year'] : null,
                'hijri_designation' => $item['hijri']['designation']['abbreviated'] ?? 'AH',
                'hijri_source' => 'aladhan',
                'hijri_is_verified' => false,
            ];
        }

        return $mapped;
    }

    private function normalizeGregorianDate(array $item): ?string
    {
        $date = $item['gregorian']['date'] ?? null;

        if (! $date) {
            return null;
        }

        // Format umum dari API biasanya dd-mm-yyyy
        return Carbon::createFromFormat('d-m-Y', $date)->toDateString();
    }

    private function normalizeHijriMonthName(?string $monthName): ?string
    {
        if (! $monthName) {
            return null;
        }

        return [
            'Muḥarram' => 'Muharram',
            'Safar' => 'Safar',
            'Rabīʿ al-awwal' => 'Rabiul Awal',
            'Rabīʿ al-thānī' => 'Rabiul Akhir',
            'Jumādá al-ūlá' => 'Jumadil Awal',
            'Jumādá al-ākhirah' => 'Jumadil Akhir',
            'Rajab' => 'Rajab',
            'Shaʿbān' => 'Syaban',
            'Ramaḍān' => 'Ramadhan',
            'Shawwāl' => 'Syawal',
            'Dhū al-Qaʿdah' => 'Dzulqaidah',
            'Dhū al-Ḥijjah' => 'Dzulhijjah',
        ][$monthName] ?? $monthName;
    }
}
