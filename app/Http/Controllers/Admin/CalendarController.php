<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter bulan dan tahun, default ke bulan saat ini
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        $date = Carbon::createFromDate($year, $month, 1);
        
        // Ambil semua jadwal di bulan ini beserta relasi usernya
        $schedules = Schedule::with('user')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        // Hitung total jadwal bulan ini
        $totalSchedulesThisMonth = $schedules->count();

        // Hitung jadwal rill minggu ini (jika melihat bulan saat ini)
        $totalSchedulesThisWeek = 0;
        $now = Carbon::now();
        if ($month == $now->month && $year == $now->year) {
            $startOfWeek = $now->copy()->startOfWeek();
            $endOfWeek = $now->copy()->endOfWeek();
            
            $totalSchedulesThisWeek = Schedule::whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])->count();
        }

        // Group by Date like Intern Schedule
        $schedulesByDate = $schedules->groupBy(function ($schedule) {
            return Carbon::parse($schedule->date)->format('Y-m-d');
        });
        
        $daysInMonth = $date->daysInMonth;
        
        // Pad for empty cells at the start of the month
        $startOfMonth = clone $date;
        $startOfMonth->startOfMonth();
        $firstDayOfWeek = $startOfMonth->dayOfWeekIso; // 1 (Mon) - 7 (Sun)
        $emptyCells = $firstDayOfWeek - 1;

        return view('admin.calendar', compact('schedulesByDate', 'schedules', 'daysInMonth', 'emptyCells', 'firstDayOfWeek', 'date', 'month', 'year', 'totalSchedulesThisMonth', 'totalSchedulesThisWeek'));
    }

    public function sendReminder()
    {
        $today = Carbon::today()->format('Y-m-d');
        
        // Cari user yang punya jadwal hari ini
        $schedulesToday = Schedule::with('user')->where('date', $today)->get();
        
        $sentCount = 0;

        foreach ($schedulesToday as $schedule) {
            $user = $schedule->user;
            
            // Cek apakah user sudah absen hari ini
            $hasCheckedIn = \App\Models\Attendance::where('user_id', $user->id)
                ->where('date', $today)
                ->exists();
                
            if (!$hasCheckedIn && !empty($user->phone_number)) {
                $target = $user->phone_number;
                $message = "Halo {$user->name},\n\nSistem mencatat Anda memiliki jadwal magang hari ini tanggal " . Carbon::today()->translatedFormat('d F Y') . " namun belum melakukan *Check-in*.\n\nSegera akses Dashboard ViaSpace dan lakukan presensi.\n\nTerima Kasih!\nAdmin ViaSpace";
                
                $response = \App\Services\FonnteService::sendMessage($target, $message);
                if ($response && isset($response['status']) && $response['status']) {
                    $sentCount++;
                }
            }
        }

        if ($sentCount > 0) {
            return redirect()->back()->with('success', "Reminder WhatsApp berhasil dikirim ke $sentCount siswa yang belum check-in.");
        }

        return redirect()->back()->with('info', "Tidak ada pesan yang perlu dikirim. (Semua sudah check-in atau tidak ada jadwal hari ini)");
    }
}
