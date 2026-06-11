<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CalendarDate;
use App\Services\Calendar\CalendarAvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function __construct(
        private readonly CalendarAvailabilityService $availabilityService
    ) {}

    public function month(Request $request)
    {
        $calendar = $request->query('calendar', 'gregorian');
        $year = (int) $request->query('year', now()->year);
        $month = (int) $request->query('month', now()->month);

        $query = CalendarDate::query();
        $availability = ['status' => 'available', 'generated' => false];

        if ($calendar === 'gregorian') {
            if ($month < 1 || $month > 12) {
                return response()->json(['message' => 'Bulan tidak valid.'], 422);
            }

            $availability = $this->availabilityService->ensureMonthAvailable($year, $month);

            $start = Carbon::create($year, $month, 1)->startOfMonth();
            $end = $start->copy()->endOfMonth();

            $query->whereBetween('gregorian_date', [
                $start->toDateString(),
                $end->toDateString(),
            ]);
        } else {
            $colPrefix = $calendar . '_';
            $query->where($colPrefix . 'year', $year)
                  ->where($colPrefix . 'month', $month);
        }

        $days = $query->get()
            ->sortByDesc('updated_at')
            ->unique(fn ($day) => explode(' ', $day->gregorian_date)[0])
            ->sortBy(fn ($day) => explode(' ', $day->gregorian_date)[0])
            ->keyBy(fn ($day) => explode(' ', $day->gregorian_date)[0]);

        return response()->json([
            'status' => 'success',
            'availability' => $availability,
            'calendar' => $calendar,
            'year' => $year,
            'month' => $month,
            'days' => $days,
        ]);
    }

    public function convert(Request $request)
    {
        $from = $request->query('from_calendar');
        $day = (int) $request->query('day');
        $month = (int) $request->query('month');
        $year = (int) $request->query('year');

        if (! $from || ! $day || ! $month || ! $year) {
            return response()->json(['message' => 'Parameter tidak lengkap.'], 422);
        }

        $query = CalendarDate::query();

        switch ($from) {
            case 'gregorian':
                $query->where('gregorian_day', $day)
                      ->where('gregorian_month', $month)
                      ->where('gregorian_year', $year);
                break;
            case 'hijri':
                $query->where('hijri_day', $day)
                      ->where('hijri_month', $month)
                      ->where('hijri_year', $year);
                break;
            case 'javanese':
                $query->where('javanese_day', $day)
                      ->where('javanese_month', $month)
                      ->where('javanese_year', $year);
                break;
            case 'sundanese':
                $query->where('sundanese_day', $day)
                      ->where('sundanese_month', $month)
                      ->where('sundanese_year', $year);
                break;
            default:
                return response()->json(['message' => 'Kalender asal tidak valid.'], 422);
        }

        $result = $query->latest('updated_at')->first();

        if (! $result) {
            return response()->json(['message' => 'Tanggal tidak ditemukan di rentang data kami (1950-2050).'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }
}
