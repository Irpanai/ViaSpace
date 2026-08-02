<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');
        
        $presentToday = Attendance::where('date', $today)->count();
        $scheduledToday = Schedule::where('date', $today)->count();

        // Get all interns
        $interns = User::where('role', 'intern')->get();

        // Get IDs of interns scheduled for today
        $scheduledUserIds = Schedule::where('date', $today)->pluck('user_id')->toArray();

        // Interns who are scheduled for today but haven't checked in
        $missingCheckIn = $interns->filter(function($intern) use ($today, $scheduledUserIds) {
            if (!in_array($intern->id, $scheduledUserIds)) {
                return false; // Not scheduled today, so they are not missing check-in
            }
            return !Attendance::where('user_id', $intern->id)->where('date', $today)->exists();
        });

        // Interns who have checked in but haven't checked out today
        $missingCheckOut = $interns->filter(function($intern) use ($today) {
            return Attendance::where('user_id', $intern->id)
                             ->where('date', $today)
                             ->whereNotNull('check_in_time')
                             ->whereNull('check_out_time')
                             ->exists();
        });

        return view('admin.dashboard', compact('presentToday', 'scheduledToday', 'missingCheckIn', 'missingCheckOut'));
    }
}
