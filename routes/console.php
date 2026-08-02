<?php

use Illuminate\Support\Facades\Schedule;
use App\Models\Setting;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use App\Services\FonnteService;

// Morning Reminder (30 mins before start time)
Schedule::call(function () {
    $startTimeStr = Setting::where('key', 'shift_start_time')->value('value') ?? '09:00';
    $targetTime = Carbon::parse($startTimeStr)->subMinutes(30)->format('H:i');
    
    if (Carbon::now()->format('H:i') === $targetTime) {
        $today = Carbon::today()->format('Y-m-d');
        $interns = User::whereHas('schedules', function ($q) use ($today) {
            $q->where('date', $today);
        })->get();

        foreach ($interns as $intern) {
            if ($intern->phone_number) {
                // Check if they haven't checked in
                $hasCheckedIn = Attendance::where('user_id', $intern->id)->where('date', $today)->exists();
                
                if (!$hasCheckedIn) {
                    $jamMasuk = Carbon::parse($startTimeStr)->format('H:i');
                    $message = "*ViaSpace - PT Via Digital Indonesia*\n🔗 viaspace.viadigital.id\n\nHi {$intern->name}, hari ini kamu ada jadwal shift magang di kantor, lho. Yuk, segera lakukan Check-In di https://viaspace.viadigital.id sebelum jam masuk ({$jamMasuk} WITA).\nTerima kasih dan semangat!";
                    
                    FonnteService::sendMessage($intern->phone_number, $message);
                }
            }
        }
    }
})->everyMinute();

// Evening Reminder (Exactly at end time)
Schedule::call(function () {
    $endTimeStr = Setting::where('key', 'shift_end_time')->value('value') ?? '17:00';
    $targetTime = Carbon::parse($endTimeStr)->format('H:i');
    
    if (Carbon::now()->format('H:i') === $targetTime) {
        $today = Carbon::today()->format('Y-m-d');
        
        // Find interns who checked in but haven't checked out
        $attendances = Attendance::where('date', $today)
            ->whereNotNull('check_in_time')
            ->whereNull('check_out_time')
            ->with('user')
            ->get();

        foreach ($attendances as $attendance) {
            if ($attendance->user->phone_number) {
                $message = "*ViaSpace - PT Via Digital Indonesia*\n🔗 viaspace.viadigital.id\n\nHi {$attendance->user->name}, kamu belum mengisi Logbook dan Check-Out hari ini. Yuk, laporkan hasil kerjamu dan selesaikan absensi di https://viaspace.viadigital.id sebelum jam 23:59 WITA agar tidak ter-Check-Out paksa oleh sistem.\nTerima kasih.";
                FonnteService::sendMessage($attendance->user->phone_number, $message);
            }
        }
    }
})->everyMinute();

// Auto-Checkout Penalty at 23:59
Schedule::call(function () {
    $today = Carbon::today()->format('Y-m-d');
    
    $attendances = Attendance::where('date', $today)
        ->whereNotNull('check_in_time')
        ->whereNull('check_out_time')
        ->get();

    foreach ($attendances as $attendance) {
        $attendance->update([
            'check_out_time' => Carbon::now(),
            'status' => 'auto-checkout',
        ]);

        $attendance->logbook()->create([
            'category' => 'Other',
            'description' => 'Sistem: Tidak mengisi logbook dan lupa check-out',
            'link' => null,
        ]);
    }
})->dailyAt('23:59');
