<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\Attendance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveRequest::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leaves = $query->paginate(15)->withQueryString();

        return view('admin.leaves.index', compact('leaves'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $leave = LeaveRequest::findOrFail($id);
        $leave->status = $request->status;
        $leave->admin_notes = $request->admin_notes;
        $leave->save();

        // If approved, create/update attendance records
        if ($leave->status === 'approved') {
            $period = CarbonPeriod::create($leave->start_date, $leave->end_date);
            
            foreach ($period as $date) {
                // Determine status for Attendance table
                $attStatus = $leave->type === 'sick' ? 'sick' : 'permission';

                Attendance::updateOrCreate(
                    [
                        'user_id' => $leave->user_id,
                        'date' => $date->format('Y-m-d'),
                    ],
                    [
                        'status' => $attStatus,
                        'leave_reason' => $leave->reason,
                        'leave_proof_path' => $leave->proof_path,
                    ]
                );
            }
        } else {
            // If rejected and attendance was previously created, we might want to delete it.
            // But for simplicity, we just leave it or let admin handle it manually.
            // Let's delete attendance if it was purely sick/permission and has no check-in
            $period = CarbonPeriod::create($leave->start_date, $leave->end_date);
            foreach ($period as $date) {
                Attendance::where('user_id', $leave->user_id)
                          ->where('date', $date->format('Y-m-d'))
                          ->whereNull('check_in_time')
                          ->whereIn('status', ['sick', 'permission'])
                          ->delete();
            }
        }

        return redirect()->route('admin.leaves.index')->with('success', 'Status pengajuan berhasil diperbarui.');
    }
}
