<?php

namespace Database\Seeders;

use App\Models\SettingSchedule;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SettingScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds:
     */
    public function run(): void
    {
        $settings = [
            [
                'day'           => 'Senin',
                'start_time'    => '07:00',
                'end_time'      => '16:00'
            ],
            [
                'day'           => 'Selasa',
                'start_time'    => '07:00',
                'end_time'      => '16:00'
            ],
            [
                'day'           => 'Rabu',
                'start_time'    => '07:00',
                'end_time'      => '16:00'
            ],
            [
                'day'           => 'Kamis',
                'start_time'    => '07:00',
                'end_time'      => '16:00'
            ],
            [
                'day'           => 'Jumat',
                'start_time'    => '07:00',
                'end_time'      => '16:00'
            ],
            [
                'day'           => 'Sabtu',
                'start_time'    => '07:00',
                'end_time'      => '16:00'
            ],
        ];

        foreach ($settings as $setting) {
            SettingSchedule::create([
                'id' => Str::uuid('id'),
                'day' => $setting['day'],
                'start_time' => $setting['start_time'],
                'end_time' => $setting['end_time']
            ]);
        }
    }
}
