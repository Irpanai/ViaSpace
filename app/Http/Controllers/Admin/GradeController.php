<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\User;
use Carbon\Carbon;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $year = Carbon::parse($month)->year;
        $monthNum = Carbon::parse($month)->format('m');

        $interns = User::where('role', 'intern')->with(['grades' => function($q) use ($monthNum, $year) {
            $q->where('month', $monthNum)->where('year', $year);
        }])->get();

        return view('admin.grades.index', compact('interns', 'month', 'monthNum', 'year'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|string',
            'year' => 'required|integer',
            'discipline_score' => 'required|integer|min:0|max:100',
            'teamwork_score' => 'required|integer|min:0|max:100',
            'skill_score' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $average = ($request->discipline_score + $request->teamwork_score + $request->skill_score) / 3;

        Grade::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'month' => $request->month,
                'year' => $request->year,
            ],
            [
                'discipline_score' => $request->discipline_score,
                'teamwork_score' => $request->teamwork_score,
                'skill_score' => $request->skill_score,
                'average_score' => $average,
                'notes' => $request->notes,
            ]
        );

        return back()->with('success', 'Nilai berhasil disimpan.');
    }
}
