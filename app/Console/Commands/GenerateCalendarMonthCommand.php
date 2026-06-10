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
