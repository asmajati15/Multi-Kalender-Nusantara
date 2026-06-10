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
