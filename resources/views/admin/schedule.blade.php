@extends('layouts.admin')
@section('title', 'Generator Jadwal Magang')

@section('content')
<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Kuota Jadwal</h2>
        <p class="text-gray-500 text-sm">Atur batas maksimal siswa yang dapat hadir per hari dan jalankan auto-generator.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Pengaturan Kuota Harian -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col">
        <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            Pengaturan Kuota Harian
        </h3>
        
        <form action="{{ route('admin.schedule.store') }}" method="POST" class="flex-1 flex flex-col">
            @csrf
            <div class="space-y-4 mb-6">
                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                    <div class="flex items-center justify-between p-3 rounded-xl border border-gray-100 bg-gray-50 hover:border-orange-200 transition-colors group">
                        <label class="font-medium text-gray-700 w-1/3">{{ $hari }}</label>
                        <div class="w-2/3 flex items-center gap-3">
                            <input type="number" name="quota[{{ $hari }}]" value="{{ $scheduleSettings[$hari] ?? 0 }}" min="0" required class="flex-1 border border-gray-200 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500 p-2 text-center font-mono">
                            <span class="text-xs text-gray-400 group-hover:text-orange-500 transition-colors w-10">Siswa</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-auto">
                <button type="submit" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-bold py-3.5 px-4 rounded-xl transition duration-200 shadow-md">
                    Simpan Konfigurasi Kuota
                </button>
            </div>
        </form>
    </div>

    <!-- Generate Jadwal Otomatis -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col">
        <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            Generate Jadwal Otomatis
        </h3>
        
        <div class="bg-blue-50/50 rounded-2xl border border-blue-100 p-5 mb-6">
            <p class="text-sm text-blue-800 leading-relaxed">
                Gunakan alat ini untuk membuat jadwal secara otomatis berdasarkan pengaturan kuota di samping. Jadwal akan dibuat untuk bulan yang Anda pilih. 
                <strong>Peringatan:</strong> Jadwal yang sudah ada pada bulan tersebut akan dihapus dan diganti baru.
            </p>
        </div>

        <form action="{{ route('admin.schedule.generate') }}" method="POST" class="mt-auto">
            @csrf
            <div class="space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Bulan</label>
                        <select name="month" required class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3 bg-gray-50 text-gray-700">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ date('m') == $m ? 'selected' : '' }}>
                                    {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Tahun</label>
                        <select name="year" required class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3 bg-gray-50 text-gray-700">
                            @foreach(range(date('Y'), date('Y')+2) as $y)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <button type="submit" onclick="return confirm('Peringatan: Membuat jadwal otomatis akan mereset semua jadwal yang sudah ada di bulan tersebut. Lanjutkan?')" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3.5 px-4 rounded-xl transition duration-300 shadow-lg shadow-blue-500/30 transform hover:-translate-y-0.5">
                        Jalankan Auto-Generator
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
