<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = LeaveRequest::where('user_id', auth()->id())
                              ->orderBy('created_at', 'desc')
                              ->paginate(10);
                              
        return view('intern.leave.index', compact('leaves'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:sick,permission',
            'reason' => 'required|string|max:500',
            'proof' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $proofPath = null;
        if ($request->hasFile('proof')) {
            $proofPath = $request->file('proof')->store('leave_proofs', 'public');
        }

        LeaveRequest::create([
            'user_id' => auth()->id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'type' => $request->type,
            'reason' => $request->reason,
            'proof_path' => $proofPath,
            'status' => 'pending',
        ]);

        return redirect()->route('intern.leave')->with('success', 'Pengajuan berhasil dikirim dan sedang menunggu persetujuan Admin.');
    }
}
