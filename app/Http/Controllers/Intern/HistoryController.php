<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LogbookExport;
use Barryvdh\DomPDF\Facade\Pdf;

class HistoryController extends Controller
{
    private function getFilteredQuery(Request $request, $userId)
    {
        $query = Attendance::with('logbook')->where('user_id', $userId);

        if ($request->filled('filter_type')) {
            $type = $request->filter_type;
            if ($type === 'this_week') {
                $query->whereBetween('date', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()]);
            } elseif ($type === 'this_month') {
                $query->whereMonth('date', \Carbon\Carbon::now()->month)
                      ->whereYear('date', \Carbon\Carbon::now()->year);
            } elseif ($type === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            }
        }

        return $query->orderBy('date', 'desc');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = $this->getFilteredQuery($request, $user->id);
        
        $attendances = $query->paginate(10)->appends($request->all());
            
        // Statistics (applied to filtered data)
        $cloneForStats = clone $query;
        $allFiltered = $cloneForStats->get();
        $totalHadir = $allFiltered->where('status', 'present')->count();
        $totalIzin = $allFiltered->where('status', 'permit')->count();
        $totalSakit = $allFiltered->where('status', 'sick')->count();

        return view('intern.history', compact('attendances', 'totalHadir', 'totalIzin', 'totalSakit'));
    }

    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        $filters = $request->only(['filter_type', 'start_date', 'end_date']);
        return Excel::download(new LogbookExport($user->id, $filters), 'Riwayat_Logbook_' . $user->name . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        $attendances = $this->getFilteredQuery($request, $user->id)->get();
            
        $pdf = Pdf::loadView('intern.exports.logbook_pdf', compact('attendances', 'user'));
        
        return $pdf->download('Riwayat_Logbook_' . $user->name . '.pdf');
    }
}
