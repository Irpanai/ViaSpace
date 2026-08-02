<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\Setting;
use App\Models\Logbook;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Redirect to login if somehow not authenticated (though route middleware should handle this)
        if (!$user) {
            return redirect()->route('login');
        }

        $today = Carbon::today()->format('Y-m-d');
        
        $hasSchedule = false;
        if ($user) {
            $hasSchedule = Schedule::where('user_id', $user->id)
                ->where('date', $today)
                ->exists();
        }

        $attendance = null;
        if ($user && $hasSchedule) {
            $attendance = Attendance::where('user_id', $user->id)
                ->where('date', $today)
                ->first();
        }

        $startTime = Setting::where('key', 'shift_start_time')->value('value') ?? '09:00';
        $endTime = Setting::where('key', 'shift_end_time')->value('value') ?? '17:00';

        // Statistics
        $totalHadir = Attendance::where('user_id', $user->id)->where('status', 'present')->count();
        $totalIzin = 0; // Future implementation
        $totalSakit = 0; // Future implementation

        $officePolygon = Setting::where('key', 'office_polygon')->value('value') ?? '[]';
        $officeLat = Setting::where('key', 'office_lat')->value('value') ?? '-3.277524';
        $officeLng = Setting::where('key', 'office_lng')->value('value') ?? '114.600035';

        return view('intern.dashboard', compact('hasSchedule', 'attendance', 'today', 'startTime', 'endTime', 'totalHadir', 'totalIzin', 'totalSakit', 'officePolygon', 'officeLat', 'officeLng'));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'photo' => 'required|image|max:5120',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Fetch Polygon Location Settings
        $polygonJson = \App\Models\Setting::where('key', 'office_polygon')->value('value');
        
        if ($polygonJson && $polygonJson !== '[]') {
            $polygon = json_decode($polygonJson, true);
            $userLat = (float) $request->lat;
            $userLng = (float) $request->lng;

            // Ray-Casting Algorithm for Point in Polygon
            $isInside = false;
            $verticesCount = count($polygon);
            $j = $verticesCount - 1;

            for ($i = 0; $i < $verticesCount; $i++) {
                $latI = (float) $polygon[$i][0];
                $lngI = (float) $polygon[$i][1];
                $latJ = (float) $polygon[$j][0];
                $lngJ = (float) $polygon[$j][1];

                if ((($lngI > $userLng) != ($lngJ > $userLng)) &&
                    ($userLat < ($latJ - $latI) * ($userLng - $lngI) / ($lngJ - $lngI) + $latI)) {
                    $isInside = !$isInside;
                }
                $j = $i;
            }

            if (!$isInside) {
                return redirect()->back()->with('error', 'Gagal Check-In: Anda berada di luar area presensi (Geofence) yang telah ditentukan oleh Admin.');
            }
        }

        $today = Carbon::today()->format('Y-m-d');
        
        $photoPath = $request->file('photo')->store('attendances', 'public');

        $startTimeStr = Setting::where('key', 'shift_start_time')->value('value') ?? '09:00';
        $shiftStart = Carbon::parse($today . ' ' . $startTimeStr);
        $lateLimit = $shiftStart->copy()->addMinutes(30);

        $status = 'present';
        if (Carbon::now()->greaterThan($lateLimit)) {
            $status = 'late';
        }

        $attendance = Attendance::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            [
                'check_in_time' => Carbon::now(),
                'check_in_lat' => $request->lat,
                'check_in_lng' => $request->lng,
                'photo_path' => $photoPath,
                'status' => $status,
            ]
        );

        return redirect()->back()->with('success', 'Berhasil Check-In');
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'description' => 'required|string|min:50',
            'link' => 'nullable|string',
            'photos' => 'nullable|array|max:2',
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'lat_out' => 'required|numeric',
            'lng_out' => 'required|numeric',
            'photo_out' => 'required|image|max:5120',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $today = Carbon::today()->format('Y-m-d');

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            return redirect()->back()->with('error', 'Gagal Check-Out: Anda belum Check-In hari ini.');
        }

        if ($attendance->check_out_time) {
            return redirect()->back()->with('error', 'Anda sudah Check-Out hari ini.');
        }

        // Fetch Polygon Location Settings
        $polygonJson = \App\Models\Setting::where('key', 'office_polygon')->value('value');
        
        if ($polygonJson && $polygonJson !== '[]') {
            $polygon = json_decode($polygonJson, true);
            $userLat = (float) $request->lat_out;
            $userLng = (float) $request->lng_out;

            // Ray-Casting Algorithm for Point in Polygon
            $isInside = false;
            $verticesCount = count($polygon);
            $j = $verticesCount - 1;

            for ($i = 0; $i < $verticesCount; $i++) {
                $latI = (float) $polygon[$i][0];
                $lngI = (float) $polygon[$i][1];
                $latJ = (float) $polygon[$j][0];
                $lngJ = (float) $polygon[$j][1];

                if ((($lngI > $userLng) != ($lngJ > $userLng)) &&
                    ($userLat < ($latJ - $latI) * ($userLng - $lngI) / ($lngJ - $lngI) + $latI)) {
                    $isInside = !$isInside;
                }
                $j = $i;
            }

            if (!$isInside) {
                return redirect()->back()->with('error', 'Gagal Check-Out: Anda berada di luar area presensi (Geofence) yang telah ditentukan oleh Admin.');
            }
        }

        // Handle Photo Upload
        $photoPath1 = null;
        $photoPath2 = null;

        if ($request->hasFile('photos')) {
            $photos = $request->file('photos');
            if (isset($photos[0])) {
                $photoPath1 = $photos[0]->store('logbooks', 'public');
            }
            if (isset($photos[1])) {
                $photoPath2 = $photos[1]->store('logbooks', 'public');
            }
        }

        $photoOutPath = $request->file('photo_out')->store('attendances', 'public');

        $attendance->update([
            'check_out_time' => Carbon::now(),
            'check_out_lat' => $request->lat_out,
            'check_out_lng' => $request->lng_out,
            'check_out_photo_path' => $photoOutPath,
        ]);

        Logbook::create([
            'attendance_id' => $attendance->id,
            'category' => $request->category,
            'description' => $request->description,
            'link' => $request->link,
            'photo_path' => $photoPath1,
            'photo_path_2' => $photoPath2,
        ]);

        return redirect()->back()->with('success', 'Berhasil Check-Out dan mengisi logbook!');
    }
}
