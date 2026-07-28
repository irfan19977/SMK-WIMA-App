<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SettingScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            ['day' => 'Senin',   'start' => '07:00', 'end' => '15:00'],
            ['day' => 'Selasa',  'start' => '07:00', 'end' => '15:00'],
            ['day' => 'Rabu',    'start' => '07:00', 'end' => '15:00'],
            ['day' => 'Kamis',   'start' => '07:00', 'end' => '15:00'],
            ['day' => 'Jumat',   'start' => '07:00', 'end' => '15:00'],
            ['day' => 'Sabtu',   'start' => '07:00', 'end' => '12:00'],
        ];

        foreach ($schedules as $schedule) {
            DB::table('setting_schedule')->insert([
                'id'         => Str::uuid()->toString(),
                'day'        => $schedule['day'],
                'start_time' => $schedule['start'],
                'end_time'   => $schedule['end'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info(count($schedules) . ' setting schedule days seeded.');
    }
}
