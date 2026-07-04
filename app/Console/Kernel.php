<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\GenerateSitemap::class,
        \App\Console\Commands\NotifyMissingLessonAttendance::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Generate sitemap daily at 2 AM
        $schedule->command('sitemap:generate')->dailyAt('02:00');

        // Cek siswa yang tidak hadir di pelajaran setiap 5 menit pada hari kerja (06:00 - 16:00)
        $schedule->command('attendance:notify-missing-lesson')
            ->everyFiveMinutes()
            ->weekdays()
            ->between('06:00', '16:00');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
