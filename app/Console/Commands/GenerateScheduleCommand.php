<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateScheduleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:generate {--week=next}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate smart auto-schedule for interns for the given week (current or next)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating smart auto-schedule...');

        $interns = \App\Models\User::where('role', 'intern')->get();
        if ($interns->count() !== 10) {
            $this->warn('There are not exactly 10 interns in the system. Current count: ' . $interns->count());
            // Proceed anyway, but the math might be slightly off.
        }

        $startDate = $this->option('week') === 'current' 
            ? \Carbon\Carbon::now()->startOfWeek() 
            : \Carbon\Carbon::now()->addWeek()->startOfWeek();

        // Check if schedule already exists for this week
        $existing = \App\Models\Schedule::whereBetween('date', [
            $startDate->copy()->format('Y-m-d'),
            $startDate->copy()->endOfWeek()->format('Y-m-d')
        ])->exists();

        if ($existing) {
            $this->error('Schedules already exist for the week starting ' . $startDate->format('Y-m-d'));
            return;
        }

        // Mon-Fri: 5 days, 4 slots each = 20 slots
        $weekdays = [];
        for ($i = 0; $i < 5; $i++) {
            $dateStr = $startDate->copy()->addDays($i)->format('Y-m-d');
            for ($j = 0; $j < 4; $j++) {
                $weekdays[] = $dateStr;
            }
        }

        // Shuffle until every intern gets 2 distinct days
        $validDistribution = false;
        $internSchedules = [];

        while (!$validDistribution) {
            shuffle($weekdays);
            $validDistribution = true;
            $internSchedules = [];
            $slotIndex = 0;

            foreach ($interns as $intern) {
                if ($slotIndex + 1 >= count($weekdays)) {
                    // Not enough slots if there are more than 10 interns, or some other math error
                    break;
                }
                $day1 = $weekdays[$slotIndex];
                $day2 = $weekdays[$slotIndex + 1];
                
                if ($day1 === $day2) {
                    $validDistribution = false;
                    break;
                }
                
                $internSchedules[$intern->id] = [$day1, $day2];
                $slotIndex += 2;
            }
        }

        // Save Weekday Schedules
        foreach ($internSchedules as $internId => $days) {
            foreach ($days as $day) {
                \App\Models\Schedule::create([
                    'user_id' => $internId,
                    'date' => $day,
                ]);
            }
        }

        // Saturday: Full team
        $saturday = $startDate->copy()->addDays(5)->format('Y-m-d');
        foreach ($interns as $intern) {
            \App\Models\Schedule::create([
                'user_id' => $intern->id,
                'date' => $saturday,
            ]);
        }

        $this->info('Schedule generated successfully for the week starting ' . $startDate->format('Y-m-d'));
    }
}
