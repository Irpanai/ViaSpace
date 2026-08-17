@extends('layouts.admin')

@section('title', 'Data Siswa Magang')

@section('content')
<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Siswa Magang</h2>
        <p class="text-gray-500 text-sm">Kelola daftar seluruh siswa magang yang terdaftar di sistem.</p>
    </div>
    <button onclick="document.getElementById('addInternModal').classList.remove('hidden')" class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-medium py-2 px-4 rounded-xl shadow-lg shadow-orange-500/30 transition-all flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Siswa Baru
    </button>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Siswa</th>
                    <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Email</th>
                    <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">NIM/NISN</th>
                    <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Asal Sekolah/Kampus</th>
                    <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Bergabung Pada</th>
                    <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($interns as $intern)
                <tr class="hover:bg-gray-50/50 transition-colors group {{ !$intern->is_active ? 'opacity-60 bg-gray-50/50' : '' }}">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0 flex items-center justify-center font-bold text-gray-400">
                                @if($intern->avatar)
                                    <img src="{{ Storage::url($intern->avatar) }}" alt="{{ $intern->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($intern->name, 0, 1) }}
                                @endif
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">
                                    {{ $intern->name }}
                                    @if($intern->is_active)
                                        <span class="ml-2 inline-block px-2 py-0.5 text-[10px] font-bold bg-green-100 text-green-600 rounded-md">AKTIF</span>
                                    @else
                                        <span class="ml-2 inline-block px-2 py-0.5 text-[10px] font-bold bg-red-100 text-red-600 rounded-md">NONAKTIF</span>
                                    @endif
                                </div>
                                <div class="text-xs text-orange-500 font-medium">{{ $intern->nickname ?? 'Belum ada panggilan' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-600">{{ $intern->email }}</td>
                    <td class="py-4 px-6 text-sm text-gray-600 font-medium">{{ $intern->nim ?? '-' }}</td>
                    <td class="py-4 px-6 text-sm text-gray-600">{{ $intern->school ?? '-' }}</td>
                    <td class="py-4 px-6 text-sm text-gray-500">{{ $intern->created_at->translatedFormat('d M Y') }}</td>
                    <td class="py-4 px-6 text-right">
                        <form action="{{ route('admin.interns.toggleActive', $intern->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin {{ $intern->is_active ? 'menonaktifkan' : 'mengaktifkan' }} siswa ini?');">
                            @csrf
                            <button type="submit" class="p-2 {{ $intern->is_active ? 'text-orange-500 hover:bg-orange-50' : 'text-green-500 hover:bg-green-50' }} rounded-xl transition-colors" title="{{ $intern->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                @if($intern->is_active)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @endif
                            </button>
                        </form>
                        <form action="{{ route('admin.interns.resetPassword', $intern->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin mereset password siswa ini? Password baru akan digenerate secara acak.');">
                            @csrf
                            <button type="submit" class="p-2 text-blue-500 hover:bg-blue-50 rounded-xl transition-colors" title="Reset Password">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4v-4l5.618-5.618A6 6 0 0115 7h.01"></path></svg>
                            </button>
                        </form>
                        <form action="{{ route('admin.interns.destroy', $intern->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun siswa ini? Semua data terkait (jadwal, logbook) juga akan ikut terhapus atau bermasalah.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-xl transition-colors" title="Hapus Akun">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 px-6 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <p class="font-medium text-lg text-gray-400">Belum ada data siswa magang.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Siswa -->
<div id="addInternModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-xl font-bold text-gray-800">Tambah Siswa Magang</h3>
            <button onclick="document.getElementById('addInternModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="p-6">
            <form action="{{ route('admin.interns.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" required placeholder="Contoh: Budi Santoso" class="w-full border border-gray-200 rounded-xl shadow-sm focus:bg-white focus:border-orange-500 focus:ring-orange-500 p-3 bg-gray-50 transition-colors">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Email</label>
                        <input type="email" name="email" required placeholder="budi@viaspace.com" class="w-full border border-gray-200 rounded-xl shadow-sm focus:bg-white focus:border-orange-500 focus:ring-orange-500 p-3 bg-gray-50 transition-colors">
                        <p class="text-xs text-gray-500 mt-2">
                            * Password otomatis (acak) akan dibuatkan oleh sistem dan ditampilkan setelah Anda menyimpan.
                        </p>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('addInternModal').classList.add('hidden')" class="px-5 py-2.5 text-gray-600 font-medium hover:bg-gray-100 rounded-xl transition-colors">Batal</button>
                    <button type="submit" class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-medium py-2.5 px-6 rounded-xl shadow-md transition-all transform hover:-translate-y-0.5">
                        Simpan & Buat Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
