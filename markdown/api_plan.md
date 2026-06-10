````md
# Implementation Plan — Tahap Awal Generate Multi Calendar 1950–2050

## 1. Tujuan

Membuat fondasi awal backend untuk fitur **Multi Calendar** dengan sumber data utama dari tabel `calendar_dates`.

Pada tahap awal, sistem akan:

- Menyimpan data kalender harian berdasarkan tanggal Masehi sebagai anchor utama.
- Meng-generate data Kalender Masehi secara native menggunakan Laravel Carbon.
- Mengambil data Kalender Hijriyah dari endpoint publik.
- Menyiapkan struktur kolom untuk Kalender Jawa dan Kalender Sunda.
- Menyiapkan command awal untuk generate data 100 tahun, yaitu dari 1950 sampai 2050.
- Menyiapkan endpoint API frontend untuk membaca data kalender bulanan.
- Menyiapkan fallback jika data bulan/tahun yang diminta user belum tersedia.

---

## 2. Konsep Utama

Tanggal Masehi menjadi anchor utama untuk semua sistem kalender.

Contoh satu row data:

```txt
gregorian_date: 2026-06-10

Masehi:
Rabu, 10 Juni 2026

Hijriyah:
Rabu, 24 Dzulhijjah 1447 H

Jawa:
Rabu Legi, 24 Besar 1959 J

Sunda:
Buda Legi, 20 Kasa 1962 S
````

Dengan pendekatan ini, frontend cukup meminta data berdasarkan bulan dan tahun Masehi.

Contoh:

```http
GET /api/calendars/month?year=2026&month=6
```

Backend akan membaca dari tabel:

```txt
calendar_dates
```

---

## 3. Strategi Tahap Awal

Untuk tahap awal gunakan strategi berikut:

```txt
Masehi:
Generate langsung menggunakan Carbon.

Hijriyah:
Generate menggunakan endpoint publik, lalu simpan ke database.

Jawa:
Kolom database disiapkan dulu.
Data bisa null sampai algoritma internal dibuat.

Sunda:
Kolom database disiapkan dulu.
Data bisa null sampai algoritma internal dibuat.

Frontend:
Fetch data dari backend Laravel, bukan langsung ke endpoint publik.
```

---

## 4. Public Endpoint Hijriyah

Gunakan endpoint publik sebagai sumber awal data Hijriyah.

Contoh endpoint yang direkomendasikan:

```txt
AlAdhan Islamic Calendar API
Base URL:
https://api.aladhan.com/v1
```

Endpoint yang dapat digunakan untuk generate per bulan:

```http
GET https://api.aladhan.com/v1/gToHCalendar/{month}/{year}
```

Contoh:

```http
GET https://api.aladhan.com/v1/gToHCalendar/6/2026
```

Endpoint ini digunakan untuk mengambil kalender Hijriyah berdasarkan bulan dan tahun Masehi.

Catatan:

```txt
Endpoint publik hanya menjadi sumber awal.
Data hasil fetch tetap disimpan ke database sebagai cache.
Frontend tidak boleh langsung hit endpoint publik.
```

---

## 5. Kenapa Menggunakan Command

Generate kalender 1950–2050 adalah proses batch besar.

Karena itu gunakan Laravel Command, bukan Controller.

Command digunakan untuk:

```txt
- Generate data tahunan.
- Generate data bulanan.
- Fetch data Hijriyah dari endpoint publik.
- Simpan data ke database.
- Update data yang belum lengkap.
- Re-run proses jika ada koreksi.
```

Controller hanya digunakan untuk:

```txt
- Frontend membaca data kalender.
- Frontend melakukan konversi.
- Frontend meminta data bulan tertentu.
```

---

## 6. Range Data Awal

Command awal akan menyiapkan data dari:

```txt
1950 sampai 2050
```

Perkiraan jumlah row:

```txt
101 tahun x maksimal 366 hari = ±36.966 row
```

Jumlah ini masih ringan untuk database.

---

## 7. Struktur Database

Gunakan satu tabel utama:

```txt
calendar_dates
```

Setiap row mewakili satu tanggal Masehi.

### Migration

```php
Schema::create('calendar_dates', function (Blueprint $table) {
    $table->id();

    // Anchor utama
    $table->date('gregorian_date')->unique();

    // Kalender Masehi
    $table->string('gregorian_day_name')->nullable();
    $table->unsignedTinyInteger('gregorian_day')->nullable();
    $table->unsignedTinyInteger('gregorian_month')->nullable();
    $table->string('gregorian_month_name')->nullable();
    $table->unsignedSmallInteger('gregorian_year')->nullable();

    // Kalender Hijriyah
    $table->string('hijri_day_name')->nullable();
    $table->unsignedTinyInteger('hijri_day')->nullable();
    $table->unsignedTinyInteger('hijri_month')->nullable();
    $table->string('hijri_month_name')->nullable();
    $table->unsignedSmallInteger('hijri_year')->nullable();
    $table->string('hijri_designation')->nullable();
    $table->string('hijri_source')->nullable();
    $table->boolean('hijri_is_verified')->default(false);

    // Kalender Jawa
    $table->string('javanese_day_name')->nullable();
    $table->string('javanese_market_day')->nullable();
    $table->unsignedTinyInteger('javanese_day')->nullable();
    $table->unsignedTinyInteger('javanese_month')->nullable();
    $table->string('javanese_month_name')->nullable();
    $table->unsignedSmallInteger('javanese_year')->nullable();
    $table->string('javanese_designation')->nullable();
    $table->string('javanese_source')->nullable();
    $table->boolean('javanese_is_verified')->default(false);

    // Kalender Sunda
    $table->string('sundanese_day_name')->nullable();
    $table->string('sundanese_day_name_common')->nullable();
    $table->string('sundanese_market_day')->nullable();
    $table->unsignedTinyInteger('sundanese_day')->nullable();
    $table->unsignedTinyInteger('sundanese_month')->nullable();
    $table->string('sundanese_month_name')->nullable();
    $table->unsignedSmallInteger('sundanese_year')->nullable();
    $table->string('sundanese_designation')->nullable();
    $table->string('sundanese_source')->nullable();
    $table->boolean('sundanese_is_verified')->default(false);

    // Status kelengkapan data
    $table->boolean('is_generated')->default(false);
    $table->boolean('is_complete')->default(false);
    $table->text('notes')->nullable();

    $table->timestamps();

    $table->index(['gregorian_year', 'gregorian_month']);
    $table->index(['hijri_year', 'hijri_month']);
    $table->index(['javanese_year', 'javanese_month']);
    $table->index(['sundanese_year', 'sundanese_month']);
});
```

---

## 8. Model CalendarDate

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarDate extends Model
{
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
        'gregorian_date' => 'date',

        'hijri_is_verified' => 'boolean',
        'javanese_is_verified' => 'boolean',
        'sundanese_is_verified' => 'boolean',

        'is_generated' => 'boolean',
        'is_complete' => 'boolean',
    ];
}
```

---

## 9. Struktur Folder Service

Gunakan struktur berikut:

```txt
app/
├── Console/
│   └── Commands/
│       ├── GenerateCalendarRangeCommand.php
│       └── GenerateCalendarMonthCommand.php
├── Services/
│   └── Calendar/
│       ├── CalendarGeneratorService.php
│       ├── CalendarAvailabilityService.php
│       ├── HijriCalendarImportService.php
│       ├── GregorianCalendarService.php
│       ├── JavaneseCalendarService.php
│       ├── SundaneseCalendarService.php
│       └── Clients/
│           └── AladhanHijriApiClient.php
└── Models/
    └── CalendarDate.php
```

---

## 10. Config Services

Tambahkan ke `config/services.php`:

```php
'aladhan' => [
    'base_url' => env('ALADHAN_BASE_URL', 'https://api.aladhan.com/v1'),
    'calendar_method' => env('ALADHAN_CALENDAR_METHOD', null),
],
```

Tambahkan ke `.env`:

```env
ALADHAN_BASE_URL=https://api.aladhan.com/v1
ALADHAN_CALENDAR_METHOD=
```

Catatan:

```txt
ALADHAN_CALENDAR_METHOD dikosongkan dulu jika belum menentukan metode kalender.
Jika nanti ingin memakai metode tertentu, isi sesuai dokumentasi endpoint.
```

---

## 11. AladhanHijriApiClient

Client ini bertugas mengambil data Hijriyah dari endpoint publik.

```php
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
```

---

## 12. GregorianCalendarService

Service ini menyiapkan data Masehi dari Carbon.

```php
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
```

---

## 13. HijriCalendarImportService

Service ini menormalisasi response API Hijriyah dan menyimpannya ke format database.

```php
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
                'hijri_month_name' => $item['hijri']['month']['en'] ?? null,
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
}
```

Catatan:

```txt
Nama hari dari AlAdhan biasanya menggunakan bahasa Inggris.
Untuk konsistensi tampilan Indonesia, nama hari final tetap bisa memakai Carbon berdasarkan gregorian_date.
```

---

## 14. Stub JavaneseCalendarService

Pada tahap awal, service Jawa belum menghitung data asli.

```php
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
```

---

## 15. Stub SundaneseCalendarService

Pada tahap awal, service Sunda belum menghitung data asli.

```php
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
```

---

## 16. CalendarGeneratorService

Service utama untuk generate data per bulan.

```php
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
```

Catatan:

```txt
Pada tahap awal, is_complete hanya mengecek Masehi + Hijriyah.
Setelah algoritma Jawa dan Sunda selesai, is_complete harus diperluas untuk mengecek semua kalender.
```

---

## 17. Command Generate Per Bulan

Buat command:

```bash
php artisan make:command GenerateCalendarMonthCommand
```

Command:

```php
<?php

namespace App\Console\Commands;

use App\Services\Calendar\CalendarGeneratorService;
use Illuminate\Console\Command;

class GenerateCalendarMonthCommand extends Command
{
    protected $signature = 'calendar:generate-month
        {--year= : Tahun Masehi}
        {--month= : Bulan Masehi}
        {--force : Update ulang data meskipun sudah ada}';

    protected $description = 'Generate data kalender multi calendar untuk satu bulan Masehi';

    public function handle(CalendarGeneratorService $generator): int
    {
        $year = (int) ($this->option('year') ?: now()->year);
        $month = (int) ($this->option('month') ?: now()->month);
        $force = (bool) $this->option('force');

        if ($month < 1 || $month > 12) {
            $this->error('Bulan harus antara 1 sampai 12.');
            return self::FAILURE;
        }

        $this->info("Generate kalender untuk {$year}-{$month}");

        $result = $generator->generateMonth(
            year: $year,
            month: $month,
            force: $force
        );

        $this->info('Selesai.');
        $this->line("Created: {$result['created']}");
        $this->line("Updated: {$result['updated']}");
        $this->line("Skipped: {$result['skipped']}");
        $this->line("Failed: {$result['failed']}");

        return self::SUCCESS;
    }
}
```

Cara menjalankan:

```bash
php artisan calendar:generate-month --year=2026 --month=6
```

Dengan force:

```bash
php artisan calendar:generate-month --year=2026 --month=6 --force
```

---

## 18. Command Generate Range 1950–2050

Buat command:

```bash
php artisan make:command GenerateCalendarRangeCommand
```

Command:

```php
<?php

namespace App\Console\Commands;

use App\Services\Calendar\CalendarGeneratorService;
use Illuminate\Console\Command;
use Throwable;

class GenerateCalendarRangeCommand extends Command
{
    protected $signature = 'calendar:generate-range
        {--from=1950 : Tahun awal Masehi}
        {--to=2050 : Tahun akhir Masehi}
        {--force : Update ulang data meskipun sudah ada}
        {--sleep=1 : Delay antar bulan dalam detik untuk mengurangi risiko rate limit}';

    protected $description = 'Generate data kalender multi calendar untuk range tahun Masehi';

    public function handle(CalendarGeneratorService $generator): int
    {
        $from = (int) $this->option('from');
        $to = (int) $this->option('to');
        $force = (bool) $this->option('force');
        $sleep = (int) $this->option('sleep');

        if ($from > $to) {
            $this->error('Tahun awal tidak boleh lebih besar dari tahun akhir.');
            return self::FAILURE;
        }

        $totalMonths = (($to - $from) + 1) * 12;
        $bar = $this->output->createProgressBar($totalMonths);
        $bar->start();

        $summary = [
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'failed' => 0,
        ];

        for ($year = $from; $year <= $to; $year++) {
            for ($month = 1; $month <= 12; $month++) {
                try {
                    $result = $generator->generateMonth(
                        year: $year,
                        month: $month,
                        force: $force
                    );

                    foreach ($summary as $key => $value) {
                        $summary[$key] += $result[$key] ?? 0;
                    }
                } catch (Throwable $e) {
                    report($e);
                    $summary['failed']++;
                }

                $bar->advance();

                if ($sleep > 0) {
                    sleep($sleep);
                }
            }
        }

        $bar->finish();
        $this->newLine(2);

        $this->info('Generate range selesai.');
        $this->line("Created: {$summary['created']}");
        $this->line("Updated: {$summary['updated']}");
        $this->line("Skipped: {$summary['skipped']}");
        $this->line("Failed: {$summary['failed']}");

        return self::SUCCESS;
    }
}
```

Cara menjalankan default 1950–2050:

```bash
php artisan calendar:generate-range
```

Cara menjalankan eksplisit:

```bash
php artisan calendar:generate-range --from=1950 --to=2050
```

Jika ingin update ulang:

```bash
php artisan calendar:generate-range --from=1950 --to=2050 --force
```

Jika ingin memperlambat request API:

```bash
php artisan calendar:generate-range --from=1950 --to=2050 --sleep=2
```

---

## 19. Estimasi Request API

Range 1950–2050:

```txt
101 tahun x 12 bulan = 1.212 request API
```

Karena request dilakukan per bulan, ini jauh lebih aman dibanding per hari.

Jika per hari:

```txt
±36.966 request API
```

Maka gunakan endpoint per bulan.

---

## 20. CalendarAvailabilityService

Service ini digunakan saat frontend meminta bulan tertentu.

Jika data belum tersedia, sistem dapat generate bulan tersebut secara langsung untuk tahap development.

```php
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
```

Catatan:

```txt
Untuk tahap development, generate langsung saat data belum ada masih aman.
Untuk production, jika request API lambat atau sering terkena rate limit, ubah menjadi dispatch queue.
```

---

## 21. Controller API Kalender Bulanan

Route:

```php
Route::get('/api/calendars/month', [CalendarController::class, 'month']);
```

Controller:

```php
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
        $year = (int) $request->query('year', now()->year);
        $month = (int) $request->query('month', now()->month);

        if ($month < 1 || $month > 12) {
            return response()->json([
                'message' => 'Bulan tidak valid.',
            ], 422);
        }

        $availability = $this->availabilityService->ensureMonthAvailable(
            year: $year,
            month: $month
        );

        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $days = CalendarDate::query()
            ->whereBetween('gregorian_date', [
                $start->toDateString(),
                $end->toDateString(),
            ])
            ->orderBy('gregorian_date')
            ->get();

        return response()->json([
            'status' => 'success',
            'availability' => $availability,
            'year' => $year,
            'month' => $month,
            'days' => $days,
        ]);
    }
}
```

---

## 22. Response API untuk Frontend

Contoh response:

```json
{
  "status": "success",
  "availability": {
    "status": "available",
    "generated": false
  },
  "year": 2026,
  "month": 6,
  "days": [
    {
      "gregorian_date": "2026-06-10",
      "gregorian_day_name": "Rabu",
      "gregorian_day": 10,
      "gregorian_month_name": "Juni",
      "gregorian_year": 2026,
      "hijri_day": 24,
      "hijri_month_name": "Dhul Hijjah",
      "hijri_year": 1447,
      "javanese_day": null,
      "javanese_month_name": null,
      "sundanese_day": null,
      "sundanese_month_name": null
    }
  ]
}
```

---

## 23. Normalisasi Nama Bulan Hijriyah

Response API bisa menggunakan nama bulan bahasa Inggris.

Untuk UI Indonesia, siapkan mapping:

```php
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
```

Tambahkan normalisasi ini di `HijriCalendarImportService`.

---

## 24. Scheduler Optional

Setelah data awal 1950–2050 selesai, scheduler dapat dipakai untuk generate tahun berikutnya.

Contoh:

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('calendar:generate-range --from=' . now()->addYear()->year . ' --to=' . now()->addYear()->year)
    ->yearlyOn(12, 1, '02:00');
```

Atau buat command khusus yang lebih rapi:

```bash
php artisan calendar:generate-next-year
```

---

## 25. Strategi Production

Untuk production, rekomendasi:

```txt
1. Jalankan command calendar:generate-range saat deployment awal.
2. Simpan semua data 1950–2050 ke database.
3. Frontend hanya membaca dari endpoint Laravel.
4. Jangan hit API publik langsung dari frontend.
5. Jangan hit API publik setiap user membuka kalender.
6. Gunakan API publik hanya untuk import/cache.
7. Untuk tahun di luar 1950–2050, gunakan on-demand generate atau queue.
```

---

## 26. Strategi Jika API Publik Gagal

Jika endpoint publik gagal saat command berjalan:

```txt
- Catat error melalui report($e).
- Increment failed.
- Lanjutkan proses bulan berikutnya.
- Jangan hentikan seluruh proses range.
```

Untuk retry manual:

```bash
php artisan calendar:generate-month --year=2026 --month=6 --force
```

Atau retry range kecil:

```bash
php artisan calendar:generate-range --from=2026 --to=2026 --force
```

---

## 27. Status Data Jawa dan Sunda

Pada tahap awal, field Jawa dan Sunda boleh null.

Contoh:

```txt
javanese_day: null
sundanese_day: null
```

Namun kolom sudah disiapkan agar nanti bisa diisi oleh algoritma internal.

Tahap berikutnya:

```txt
1. Kumpulkan data referensi Kalender Jawa.
2. Buat JavaneseCalendarService.
3. Generate ulang data Jawa ke tabel calendar_dates.
4. Kumpulkan data referensi Kalender Sunda.
5. Buat SundaneseCalendarService.
6. Generate ulang data Sunda ke tabel calendar_dates.
```

---

## 28. Command Lanjutan untuk Generate Jawa dan Sunda

Nanti setelah algoritma tersedia, bisa dibuat command tambahan:

```bash
php artisan calendar:generate-javanese --from=1950 --to=2050
php artisan calendar:generate-sundanese --from=1950 --to=2050
```

Atau tetap gunakan command utama:

```bash
php artisan calendar:generate-range --from=1950 --to=2050 --force
```

Dengan syarat `JavaneseCalendarService` dan `SundaneseCalendarService` sudah berisi algoritma asli.

---

## 29. Alur Final Tahap Awal

```txt
Developer menjalankan command awal:
php artisan calendar:generate-range --from=1950 --to=2050

↓
Command loop tahun dan bulan.

↓
Setiap bulan:
- Generate data Masehi dengan Carbon.
- Fetch data Hijriyah dari endpoint publik.
- Simpan/update ke calendar_dates.
- Kolom Jawa dan Sunda masih null.

↓
Frontend membuka Multi Calendar.

↓
Frontend request:
GET /api/calendars/month?year=2026&month=6

↓
Backend cek calendar_dates.

↓
Jika data ada:
Return data kalender.

↓
Jika data belum ada:
Generate bulan tersebut secara on-demand.

↓
Frontend menampilkan:
- Kalender Masehi
- Kalender Hijriyah
- Placeholder Kalender Jawa
- Placeholder Kalender Sunda
```

---

## 30. Acceptance Criteria

Tahap awal dianggap selesai jika:

* Tabel `calendar_dates` tersedia.
* Model `CalendarDate` tersedia.
* Command `calendar:generate-month` tersedia.
* Command `calendar:generate-range` tersedia.
* Command default bisa generate range 1950–2050.
* Data Masehi berhasil digenerate menggunakan Carbon.
* Data Hijriyah berhasil diambil dari endpoint publik.
* Data Hijriyah berhasil disimpan ke database.
* Endpoint `/api/calendars/month` bisa mengembalikan data bulanan.
* Jika data bulan belum tersedia, backend dapat generate bulan tersebut.
* Kolom Kalender Jawa tersedia meskipun datanya masih null.
* Kolom Kalender Sunda tersedia meskipun datanya masih null.
* Frontend dapat menampilkan data Masehi dan Hijriyah dari database.
* Frontend dapat menampilkan placeholder untuk Jawa dan Sunda.
* API publik tidak dipanggil langsung dari frontend.
* API publik hanya digunakan oleh backend command/service.

---

## 31. Catatan Penting

Data Hijriyah dari API publik bisa memiliki perbedaan dengan penetapan resmi tertentu karena metode kalender dapat berbeda.

Karena itu:

```txt
- Simpan source data.
- Simpan flag hijri_is_verified.
- Siapkan mekanisme koreksi manual di tahap berikutnya.
- Jangan anggap data API sebagai final mutlak untuk semua kebutuhan resmi.
```

Untuk aplikasi umum dan tahap awal layout/data, pendekatan ini sudah cukup baik.

Untuk kebutuhan resmi, nantinya perlu validasi terhadap sumber otoritatif yang dipilih.

```
```
