@extends('layouts.intern')
@section('title', 'Profil Saya')

@section('content')

@if(auth()->user()->must_change_password)
<div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 shadow-sm flex items-start gap-4">
    <div class="p-2 bg-red-100 text-red-600 rounded-lg flex-shrink-0">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
    </div>
    <div>
        <h3 class="text-red-800 font-bold text-lg">Perhatian: Wajib Ganti Password!</h3>
        <p class="text-red-700 text-sm mt-1">Anda masih menggunakan password bawaan sistem (acak). Demi keamanan, Anda <strong>wajib mengganti password</strong> Anda di bawah ini sebelum dapat menggunakan menu lain di aplikasi ini.</p>
    </div>
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden md:col-span-1 self-start">
        <div class="p-8 border-b border-gray-100 text-center">
            <div class="w-32 h-32 mx-auto bg-gradient-to-tr from-orange-500 to-yellow-400 text-white rounded-full flex items-center justify-center font-bold text-5xl shadow-lg mb-4 relative overflow-hidden group">
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                @else
                    {{ substr($user->name, 0, 1) }}
                @endif
                <label for="avatar-upload" class="absolute inset-0 bg-black/50 hidden group-hover:flex items-center justify-center cursor-pointer transition-all">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </label>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">{{ $user->name }}</h2>
            <p class="text-gray-500 mt-1">{{ $user->email }}</p>
            <div class="mt-4 inline-block px-4 py-1.5 bg-orange-50 text-orange-600 rounded-full text-sm font-semibold border border-orange-100 shadow-sm">
                Siswa Magang
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden md:col-span-2">
        <div class="p-6 md:p-8">
            <form action="{{ route('intern.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="avatar" id="avatar-upload" class="hidden" accept="image/*" onchange="document.getElementById('avatar-name').textContent = 'Terpilih: ' + this.files[0].name; document.getElementById('avatar-name').classList.remove('hidden');">
                <div class="text-xs text-orange-600 font-medium mb-4 hidden text-center bg-orange-50 p-2 rounded-lg" id="avatar-name"></div>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="w-full border border-gray-200 rounded-xl shadow-sm bg-gray-50 focus:bg-white focus:ring-orange-500 focus:border-orange-500 p-3 transition-colors">
                            <p class="text-xs text-gray-400 mt-1">Pastikan nama lengkap sesuai dengan identitas Anda.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Panggilan</label>
                            <input type="text" name="nickname" value="{{ $user->nickname }}" placeholder="Contoh: Budi" class="w-full border border-gray-200 rounded-xl shadow-sm bg-gray-50 focus:bg-white focus:ring-orange-500 focus:border-orange-500 p-3 transition-colors">
                            <p class="text-xs text-gray-400 mt-1">Nama ini akan digunakan pada daftar di kalender jadwal magang.</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" value="{{ $user->email }}" disabled class="w-full border border-gray-200 rounded-xl shadow-sm bg-gray-100 text-gray-500 p-3 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor WhatsApp</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-gray-200 bg-gray-100 text-gray-500 sm:text-sm font-medium">
                                +62
                            </span>
                            <input type="text" name="phone_number" value="{{ $user->phone_number }}" placeholder="81234567890" class="flex-1 min-w-0 block w-full px-3 py-3 rounded-none rounded-r-xl focus:bg-white focus:ring-orange-500 focus:border-orange-500 sm:text-sm border border-gray-200 bg-gray-50 shadow-sm transition-colors">
                        </div>
                        <p class="text-xs text-orange-600 font-medium mt-2">* Nomor WA ini akan digunakan sistem untuk mengirimkan notifikasi penting terkait jadwal magang Anda.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">NIM / NISN</label>
                            <input type="text" name="nim" value="{{ $user->nim }}" placeholder="Nomor Induk Siswa/Mahasiswa" class="w-full border border-gray-200 rounded-xl shadow-sm bg-gray-50 focus:bg-white focus:ring-orange-500 focus:border-orange-500 p-3 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Asal Sekolah / Kampus</label>
                            <input type="text" name="school" value="{{ $user->school }}" placeholder="Contoh: Universitas Terbuka" class="w-full border border-gray-200 rounded-xl shadow-sm bg-gray-50 focus:bg-white focus:ring-orange-500 focus:border-orange-500 p-3 transition-colors">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap</label>
                        <textarea name="address" rows="3" class="w-full border border-gray-200 rounded-xl shadow-sm bg-gray-50 focus:bg-white focus:ring-orange-500 focus:border-orange-500 p-3 transition-colors" placeholder="Masukkan alamat lengkap domisili Anda...">{{ $user->address }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Instagram (Opsional)</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-gray-200 bg-gray-100 text-gray-500 sm:text-sm font-medium">
                                @
                            </span>
                            <input type="text" name="instagram" value="{{ $user->instagram }}" placeholder="username_ig" class="flex-1 min-w-0 block w-full px-3 py-3 rounded-none rounded-r-xl focus:bg-white focus:ring-orange-500 focus:border-orange-500 sm:text-sm border border-gray-200 bg-gray-50 shadow-sm transition-colors">
                        </div>
                    </div>

                    <hr class="border-gray-100 my-6">

                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Keamanan Akun (Ganti Password)
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru (Opsional)</label>
                            <input type="password" name="password" placeholder="Biarkan kosong jika tidak ingin ganti" class="w-full border border-gray-200 rounded-xl shadow-sm bg-gray-50 focus:bg-white focus:ring-orange-500 focus:border-orange-500 p-3 transition-colors">
                            @if(auth()->user()->must_change_password)
                                <p class="text-xs text-red-500 mt-1 font-semibold">* Wajib diisi untuk pengguna baru.</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="w-full border border-gray-200 rounded-xl shadow-sm bg-gray-50 focus:bg-white focus:ring-orange-500 focus:border-orange-500 p-3 transition-colors">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-medium py-3 px-4 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 shadow-lg shadow-orange-500/30 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
