<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;

class SettingsController extends Controller
{
    // TIME SETTINGS
    public function time()
    {
        $startTime = Setting::where('key', 'shift_start_time')->value('value') ?? '09:00';
        $endTime = Setting::where('key', 'shift_end_time')->value('value') ?? '17:00';
        return view('admin.time', compact('startTime', 'endTime'));
    }

    public function updateTime(Request $request)
    {
        $request->validate([
            'shift_start_time' => 'required|date_format:H:i',
            'shift_end_time' => 'required|date_format:H:i',
        ]);

        Setting::updateOrCreate(['key' => 'shift_start_time'], ['value' => $request->shift_start_time]);
        Setting::updateOrCreate(['key' => 'shift_end_time'], ['value' => $request->shift_end_time]);

        return redirect()->back()->with('success', 'Pengaturan waktu shift berhasil diperbarui.');
    }

    // LOCATION SETTINGS
    public function location()
    {
        $lat = Setting::where('key', 'office_lat')->value('value') ?? '-3.277524'; // Default Borneo
        $lng = Setting::where('key', 'office_lng')->value('value') ?? '114.600035';
        $polygon = Setting::where('key', 'office_polygon')->value('value') ?? '[]'; // Default empty array JSON
        return view('admin.location', compact('lat', 'lng', 'polygon'));
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'office_lat' => 'required|numeric',
            'office_lng' => 'required|numeric',
            'office_polygon' => 'required|string', // JSON string of coordinates
        ]);

        Setting::updateOrCreate(['key' => 'office_lat'], ['value' => $request->office_lat]);
        Setting::updateOrCreate(['key' => 'office_lng'], ['value' => $request->office_lng]);
        Setting::updateOrCreate(['key' => 'office_polygon'], ['value' => $request->office_polygon]);

        return redirect()->back()->with('success', 'Titik pusat dan area batas geofence (polygon) berhasil diperbarui.');
    }

    // SCHEDULE SETTINGS
    public function schedule()
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $scheduleSettings = [];
        foreach ($days as $hari) {
            $scheduleSettings[$hari] = Setting::where('key', 'quota_' . $hari)->value('value') ?? 0;
        }
            
        return view('admin.schedule', compact('scheduleSettings'));
    }

    public function storeSchedule(Request $request)
    {
        $request->validate([
            'quota' => 'required|array',
        ]);

        foreach ($request->quota as $hari => $jumlah) {
            Setting::updateOrCreate(
                ['key' => 'quota_' . $hari],
                ['value' => $jumlah]
            );
        }

        return redirect()->back()->with('success', 'Konfigurasi kuota harian berhasil disimpan.');
    }

    public function generateSchedule(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer',
        ]);

        $month = $request->month;
        $year = $request->year;

        try {
            // Hapus jadwal lama di bulan tersebut
            Schedule::whereMonth('date', $month)->whereYear('date', $year)->delete();

            // Load kuota
            $daysMap = [
                1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 
                4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'
            ];
            
            $quotas = [];
            foreach ($daysMap as $iso => $namaHari) {
                $quotas[$iso] = (int)(Setting::where('key', 'quota_' . $namaHari)->value('value') ?? 0);
            }

            // Load intern
            $interns = User::where('role', 'intern')->get();
            if ($interns->isEmpty()) {
                return redirect()->back()->with('error', 'Gagal generate: Tidak ada siswa magang yang terdaftar.');
            }

            $date = Carbon::createFromDate($year, $month, 1);
            $daysInMonth = $date->daysInMonth;

            // Tracking jumlah shift per intern (untuk distribusi adil)
            $shiftCounts = [];
            foreach ($interns as $intern) {
                $shiftCounts[$intern->id] = 0;
            }

            $newSchedules = [];

            for ($d = 1; $d <= $daysInMonth; $d++) {
                $currentDate = Carbon::createFromDate($year, $month, $d);
                $dayOfWeek = $currentDate->dayOfWeekIso; // 1 (Mon) - 7 (Sun)
                
                $quotaToday = $quotas[$dayOfWeek];
                
                if ($quotaToday > 0) {
                    // Sort interns by lowest shift count first, then random (to prevent predictable patterns)
                    $internIds = array_keys($shiftCounts);
                    usort($internIds, function($a, $b) use ($shiftCounts) {
                        if ($shiftCounts[$a] == $shiftCounts[$b]) {
                            return rand(-1, 1);
                        }
                        return $shiftCounts[$a] - $shiftCounts[$b];
                    });

                    // Ambil intern sebanyak kuota (jika kuota > jumlah intern, batasi ke jumlah intern)
                    $take = min($quotaToday, count($internIds));
                    $selectedInternIds = array_slice($internIds, 0, $take);

                    foreach ($selectedInternIds as $id) {
                        $newSchedules[] = [
                            'user_id' => $id,
                            'date' => $currentDate->format('Y-m-d'),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        $shiftCounts[$id]++;
                    }
                }
            }

            // Bulk insert
            if (count($newSchedules) > 0) {
                Schedule::insert($newSchedules);
            }

            return redirect()->back()->with('success', 'Jadwal bulan ' . $date->translatedFormat('F Y') . ' berhasil digenerate secara adil.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal generate jadwal: ' . $e->getMessage());
        }
    }
}
