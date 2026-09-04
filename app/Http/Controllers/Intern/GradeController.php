<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grade;

class GradeController extends Controller
{
    public function index()
    {
        $grades = Grade::where('user_id', auth()->id())
                       ->orderBy('year', 'desc')
                       ->orderBy('month', 'desc')
                       ->get();

        return view('intern.grades.index', compact('grades'));
    }
}
