<?php

namespace App\Services\Calendar\Clients;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class AladhanHijriApiClient
{
    public function getGregorianToHijriCalendar(int $year, int $month): array
    {
        $baseUrl = rtrim(config('services.aladhan.base_url'), '/');

        $query = [];

        if (filled(config('services.aladhan.calendar_method'))) {
            $query['calendarMethod'] = config('services.aladhan.calendar_method');
        }

        $response = Http::timeout(30)
            ->retry(3, 1000)
            ->get("{$baseUrl}/gToHCalendar/{$month}/{$year}", $query);

        if ($response->failed()) {
            throw new RuntimeException("Gagal mengambil data Hijriyah untuk {$year}-{$month}.");
        }

        $json = $response->json();

        if (! isset($json['data']) || ! is_array($json['data'])) {
            throw new RuntimeException("Format response Hijriyah tidak valid untuk {$year}-{$month}.");
        }

        return $json['data'];
    }
}
