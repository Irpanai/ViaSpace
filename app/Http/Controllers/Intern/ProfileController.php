<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('intern.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:50',
            'phone_number' => 'nullable|string|max:20',
            'nim' => 'nullable|string|max:50',
            'school' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'instagram' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
        ];

        // Jika user wajib ganti password, maka password menjadi required
        if ($user->must_change_password) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }

        $request->validate($rules);
        
        $data = [
            'name' => $request->name,
            'nickname' => $request->nickname,
            'phone_number' => $request->phone_number,
            'nim' => $request->nim,
            'school' => $request->school,
            'address' => $request->address,
            'instagram' => $request->instagram,
        ];

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
            $data['must_change_password'] = false;
        }

        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
