<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Schedule;
use App\Models\Setting;
use Carbon\Carbon;
use App\Services\FonnteService;

class ScheduleController extends Controller
{
    public function swap(Request $request)
    {
        $request->validate([
            'schedule_id_1' => 'required|exists:schedules,id',
            'schedule_id_2' => 'required|exists:schedules,id',
        ]);

        $schedule1 = Schedule::findOrFail($request->schedule_id_1);
        $schedule2 = Schedule::findOrFail($request->schedule_id_2);

        $startTimeStr = Setting::where('key', 'shift_start_time')->value('value') ?? '09:00';
        
        // Validate H-1 hour for Schedule 1
        $date1 = Carbon::parse($schedule1->date->format('Y-m-d') . ' ' . $startTimeStr);
        if (Carbon::now()->diffInMinutes($date1, false) < 60) {
            return redirect()->back()->with('error', 'Gagal menukar. Jadwal pertama sudah lewat atau kurang dari 1 jam.');
        }

        // Validate H-1 hour for Schedule 2
        $date2 = Carbon::parse($schedule2->date->format('Y-m-d') . ' ' . $startTimeStr);
        if (Carbon::now()->diffInMinutes($date2, false) < 60) {
            return redirect()->back()->with('error', 'Gagal menukar. Jadwal kedua sudah lewat atau kurang dari 1 jam.');
        }

        // Simpan tanggal lama untuk notifikasi
        $oldDate1 = $schedule1->date->translatedFormat('l, d F Y');
        $oldDate2 = $schedule2->date->translatedFormat('l, d F Y');

        // Swap Users
        $tempUserId = $schedule1->user_id;
        $schedule1->update(['user_id' => $schedule2->user_id]);
        $schedule2->update(['user_id' => $tempUserId]);

        // Refresh to get updated users
        $schedule1->refresh();
        $schedule2->refresh();

        $newDate1 = $schedule1->date->translatedFormat('l, d F Y');
        $newDate2 = $schedule2->date->translatedFormat('l, d F Y');

        // Send WhatsApp Notification to both
        if ($schedule1->user->phone_number) {
            $msg1 = "*ViaSpace - PT Via Digital Indonesia*\n🔗 viaspace.viadigital.id\n\nHi {$schedule1->user->name}, terdapat perubahan pada jadwal shift magangmu. Jadwalmu untuk hari {$oldDate2} telah ditukar ke hari {$newDate1} oleh Admin. \nSilakan cek jadwal terbarumu selengkapnya di https://viaspace.viadigital.id.\nTerima kasih.";
            FonnteService::sendMessage($schedule1->user->phone_number, $msg1);
        }

        if ($schedule2->user->phone_number) {
            $msg2 = "*ViaSpace - PT Via Digital Indonesia*\n🔗 viaspace.viadigital.id\n\nHi {$schedule2->user->name}, terdapat perubahan pada jadwal shift magangmu. Jadwalmu untuk hari {$oldDate1} telah ditukar ke hari {$newDate2} oleh Admin. \nSilakan cek jadwal terbarumu selengkapnya di https://viaspace.viadigital.id.\nTerima kasih.";
            FonnteService::sendMessage($schedule2->user->phone_number, $msg2);
        }

        return redirect()->back()->with('success', 'Jadwal berhasil ditukar.');
    }

    public function manualAdd(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
        ]);

        // Cek apakah sudah ada jadwal di tanggal tersebut untuk user ini
        $existing = Schedule::where('user_id', $request->user_id)
            ->whereDate('date', $request->date)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Siswa tersebut sudah memiliki jadwal di tanggal yang dipilih.');
        }

        Schedule::create([
            'user_id' => $request->user_id,
            'date' => $request->date,
        ]);

        return redirect()->back()->with('success', 'Jadwal manual berhasil ditambahkan.');
    }
}
