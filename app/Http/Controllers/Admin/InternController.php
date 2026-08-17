<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InternController extends Controller
{
    public function index()
    {
        // Ambil semua user dengan role 'intern'
        $interns = User::where('role', 'intern')->orderBy('name', 'asc')->get();

        return view('admin.interns.index', compact('interns'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $rawPassword = Str::random(6);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($rawPassword),
            'role' => 'intern',
            'must_change_password' => true,
        ]);

        return redirect()->back()->with('success', "Siswa berhasil ditambahkan. Password default mereka: {$rawPassword}");
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        if ($user->role !== 'intern') {
            return redirect()->back()->with('error', 'Hanya akun siswa magang yang dapat direset passwordnya.');
        }

        $newPassword = Str::random(6);

        $user->update([
            'password' => Hash::make($newPassword),
            'must_change_password' => true,
        ]);

        return redirect()->back()->with('success', "Password untuk {$user->name} berhasil direset. Password baru: {$newPassword}");
    }

    public function toggleActive($id)
    {
        $user = User::findOrFail($id);

        if ($user->role !== 'intern') {
            return redirect()->back()->with('error', 'Hanya akun siswa magang yang dapat diubah statusnya.');
        }

        $user->update([
            'is_active' => !$user->is_active,
        ]);

        $statusStr = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Status {$user->name} berhasil {$statusStr}.");
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting non-interns just to be safe
        if ($user->role !== 'intern') {
            return redirect()->back()->with('error', 'Hanya akun siswa magang yang dapat dihapus.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Siswa magang berhasil dihapus dari sistem.');
    }
}
