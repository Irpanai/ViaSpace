<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Setting;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class ViaSpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Settings
        Setting::firstOrCreate(['key' => 'shift_start_time'], ['value' => '09:00']);
        Setting::firstOrCreate(['key' => 'shift_end_time'], ['value' => '17:00']);

        // 2. Admin
        User::firstOrCreate(
            ['email' => 'admin@viaspace.com'],
            [
                'name' => 'Admin ViaSpace',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 3. 10 Interns
        $interns = [];
        for ($i = 1; $i <= 10; $i++) {
            $interns[] = User::firstOrCreate(
                ['email' => "intern{$i}@viaspace.com"],
                [
                    'name' => "Intern {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'intern',
                    'phone_number' => '08120000' . str_pad($i, 4, '0', STR_PAD_LEFT),
                ]
            );
        }

        // 4. Generate Schedule for August 2026
        // Clear existing schedules for August 2026
        Schedule::whereBetween('date', ['2026-08-01', '2026-08-31'])->delete();

        $start = Carbon::create(2026, 8, 1);
        $end = Carbon::create(2026, 8, 31);
        
        $weekdays = [];
        
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if ($date->isSaturday()) {
                // Saturday: All 10 Interns
                foreach ($interns as $intern) {
                    Schedule::create([
                        'user_id' => $intern->id,
                        'date' => $date->format('Y-m-d'),
                    ]);
                }
            } elseif ($date->isWeekday()) {
                // Mon-Fri: collect for distribution
                for ($i = 0; $i < 4; $i++) {
                    $weekdays[] = $date->format('Y-m-d');
                }
            }
            // Sunday: off (do nothing)
        }

        // Shuffle and distribute Mon-Fri slots
        shuffle($weekdays);
        $internIds = collect($interns)->pluck('id')->toArray();
        $internSchedules = array_fill_keys($internIds, []);
        
        $internIndex = 0;
        foreach ($weekdays as $day) {
            // Find an intern that doesn't already have a schedule for this day
            $assigned = false;
            $attempts = 0;
            while (!$assigned && $attempts < count($internIds)) {
                $currentInternId = $internIds[$internIndex];
                if (!in_array($day, $internSchedules[$currentInternId])) {
                    $internSchedules[$currentInternId][] = $day;
                    $assigned = true;
                }
                $internIndex = ($internIndex + 1) % count($internIds);
                $attempts++;
            }
            
            // In rare cases of collision where all tried interns already have the day, just assign to the first to avoid infinite loop
            if (!$assigned) {
                $internSchedules[$internIds[array_rand($internIds)]][] = $day;
            }
        }

        // Insert Mon-Fri schedules
        foreach ($internSchedules as $internId => $days) {
            foreach ($days as $day) {
                Schedule::create([
                    'user_id' => $internId,
                    'date' => $day,
                ]);
            }
        }
    }
}
