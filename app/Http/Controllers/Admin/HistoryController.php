<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $interns = User::where('role', 'intern')->get();
        
        $query = Attendance::with(['user', 'logbook'])->orderBy('date', 'desc')->orderBy('check_in_time', 'desc');

        if ($request->filled('intern_id')) {
            $query->where('user_id', $request->intern_id);
        }

        if ($request->filled('month')) {
            $month = Carbon::parse($request->month)->month;
            $year = Carbon::parse($request->month)->year;
            $query->whereMonth('date', $month)->whereYear('date', $year);
        } else {
            // Default to current month if no filter
            $query->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year);
        }

        $attendances = $query->paginate(15)->withQueryString();

        return view('admin.history', compact('attendances', 'interns'));
    }
}
