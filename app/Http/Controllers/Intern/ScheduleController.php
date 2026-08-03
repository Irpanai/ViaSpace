<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        // Mendapatkan bulan dan tahun (default bulan berjalan jika tidak ada input)
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Membuat instance tanggal untuk awal bulan
        $date = Carbon::createFromDate($year, $month, 1);
        
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        // Mengambil semua jadwal dalam rentang bulan tersebut
        $schedules = Schedule::with('user')
            ->whereBetween('date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
            ->get();

        // Mengambil absensi untuk mewarnai kalender
        $attendances = \App\Models\Attendance::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d') . '_' . $item->user_id;
            });

        // Mengelompokkan jadwal berdasarkan tanggal
        // Hasilnya: ['2023-10-01' => [Schedule1, Schedule2], '2023-10-02' => [...]]
        $schedulesByDate = $schedules->groupBy(function ($schedule) {
            return Carbon::parse($schedule->date)->format('Y-m-d');
        });

        // Hitung statistik jadwal user yang login
        $userId = auth()->id();
        $mySchedulesThisMonth = $schedules->where('user_id', $userId)->count();

        // Hitung minggu ini (Senin - Minggu) HANYA JIKA bulan yang dilihat adalah bulan saat ini
        $mySchedulesThisWeek = 0;
        $now = Carbon::now();
        if ($month == $now->month && $year == $now->year) {
            $startOfWeek = $now->copy()->startOfWeek();
            $endOfWeek = $now->copy()->endOfWeek();
            
            $mySchedulesThisWeek = Schedule::where('user_id', $userId)
                ->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
                ->count();
        }

        // Menyiapkan data kalender
        $daysInMonth = $date->daysInMonth;
        // 0 (Minggu) sampai 6 (Sabtu) -> ISO: 1 (Senin) - 7 (Minggu)
        $firstDayOfWeek = $startOfMonth->dayOfWeekIso; 
        
        return view('intern.schedule', compact(
            'month', 
            'year', 
            'date', 
            'schedulesByDate', 
            'attendances',
            'daysInMonth', 
            'firstDayOfWeek',
            'mySchedulesThisMonth',
            'mySchedulesThisWeek'
        ));
    }
}
