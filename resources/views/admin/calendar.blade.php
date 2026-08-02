@extends('layouts.admin')

@section('title', 'Kalender Magang')

@section('content')
<!-- Filter/Header Bar -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">{{ $date->translatedFormat('F Y') }}</h2>
        <p class="text-gray-500 text-sm">Kalender jadwal kehadiran seluruh siswa magang</p>
    </div>
    <div class="flex items-center gap-3">
        <!-- Tambah Jadwal Button -->
        <button onclick="document.getElementById('addScheduleModal').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold text-sm hover:from-orange-600 hover:to-orange-700 transition-colors shadow-sm shadow-orange-500/30 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Jadwal
        </button>

        <!-- Kirim Reminder WA Button -->
        <form action="{{ route('admin.calendar.reminder') }}" method="POST" class="inline" onsubmit="return confirm('Kirim WhatsApp pengingat absen ke seluruh anak magang yang memiliki jadwal hari ini?')">
            @csrf
            <button type="submit" class="px-4 py-2 rounded-xl bg-green-50 text-green-600 font-semibold text-sm border border-green-100 hover:bg-green-100 transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                Kirim Pengingat WA
            </button>
        </form>

        <!-- Tukar Jadwal Button -->
        <button onclick="document.getElementById('swapScheduleModal').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-orange-50 text-orange-600 font-semibold text-sm border border-orange-100 hover:bg-orange-100 transition-colors shadow-sm flex items-center gap-2 mr-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
            Tukar Jadwal
        </button>

        @php
            $prevMonth = $date->copy()->subMonth();
            $nextMonth = $date->copy()->addMonth();
        @endphp
        
        <a href="{{ route('admin.calendar', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}" class="p-2 rounded-xl border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <a href="{{ route('admin.calendar') }}" class="px-4 py-2 rounded-xl border border-gray-200 bg-white text-gray-700 font-semibold text-sm hover:bg-gray-50 transition-colors shadow-sm">
            Bulan Ini
        </a>
        <a href="{{ route('admin.calendar', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}" class="p-2 rounded-xl border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>
</div>

<!-- Statistik Jadwal Global -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-extrabold text-gray-900">{{ $totalSchedulesThisWeek }}</span>
                <span class="text-sm font-medium text-gray-500">jadwal</span>
            </div>
            <p class="text-xs text-gray-500 font-medium">Total Jadwal Siswa Minggu Ini</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        </div>
        <div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-extrabold text-gray-900">{{ $totalSchedulesThisMonth }}</span>
                <span class="text-sm font-medium text-gray-500">jadwal</span>
            </div>
            <p class="text-xs text-gray-500 font-medium">Total Jadwal Siswa di Bulan Ini</p>
        </div>
    </div>
</div>

<!-- Calendar Grid Area -->
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-4 md:p-6 mb-8">
    
    <!-- Nama Hari -->
    <div class="grid grid-cols-7 gap-2 md:gap-4 mb-4">
        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $dayName)
            <div class="text-center">
                <span class="hidden md:inline font-bold text-xs uppercase tracking-wider text-gray-400">{{ $dayName }}</span>
                <span class="md:hidden font-bold text-xs uppercase tracking-wider text-gray-400">{{ substr($dayName, 0, 3) }}</span>
            </div>
        @endforeach
    </div>

    <!-- Tanggal -->
    <div class="grid grid-cols-7 gap-2 md:gap-4">
        {{-- Padding untuk hari sebelum tanggal 1 --}}
        @for ($i = 0; $i < $emptyCells; $i++)
            <div class="min-h-[100px] md:min-h-[140px] bg-gray-50/50 rounded-2xl border border-gray-100 border-dashed opacity-50"></div>
        @endfor

        {{-- Sel-sel tanggal --}}
        @for ($day = 1; $day <= $daysInMonth; $day++)
            @php
                $currentDateStr = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                $isToday = $currentDateStr == \Carbon\Carbon::today()->format('Y-m-d');
                $daySchedules = $schedulesByDate->get($currentDateStr, collect());
            @endphp
            
            <div class="min-h-[100px] md:min-h-[140px] p-2 md:p-3 rounded-2xl border {{ $isToday ? 'border-orange-300 bg-gradient-to-b from-orange-50/50 to-white shadow-sm ring-1 ring-orange-100' : 'border-gray-100 hover:border-gray-300 hover:shadow-md transition-all duration-300 bg-white' }} flex flex-col group relative">
                <!-- Tanggal Header -->
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-bold w-8 h-8 flex items-center justify-center rounded-xl {{ $isToday ? 'text-white bg-gradient-to-tr from-orange-500 to-orange-400 shadow-md shadow-orange-500/20' : 'text-gray-600 group-hover:bg-gray-100' }}">
                        {{ $day }}
                    </span>
                    @if($daySchedules->count() > 0)
                        <span class="text-[10px] font-bold bg-gray-100 text-gray-500 px-2 py-1 rounded-lg group-hover:bg-orange-100 group-hover:text-orange-600 transition-colors">
                            {{ $daySchedules->count() }}
                        </span>
                    @endif
                </div>

                <!-- Daftar Siswa Masuk -->
                <div class="flex-1 overflow-y-auto custom-scrollbar flex flex-wrap gap-1.5 items-start content-start">
                    @foreach($daySchedules as $schedule)
                        <div class="flex items-center gap-1.5 px-2 py-1 rounded-full bg-gray-50 border border-gray-100 hover:bg-gray-100 transition-colors" title="{{ $schedule->user->name }}">
                            <!-- Avatar Mini -->
                            <div class="w-4 h-4 md:w-5 md:h-5 rounded-full flex-shrink-0 bg-white flex items-center justify-center text-[9px] font-bold text-gray-600 overflow-hidden shadow-sm border border-gray-100">
                                @if($schedule->user->avatar)
                                    <img src="{{ Storage::url($schedule->user->avatar) }}" alt="{{ $schedule->user->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($schedule->user->name, 0, 1) }}
                                @endif
                            </div>
                            <!-- Nama -->
                            <span class="text-[9px] md:text-[10px] font-semibold truncate text-gray-600">
                                {{ $schedule->user->nickname ? $schedule->user->nickname : explode(' ', trim($schedule->user->name))[0] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endfor
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: rgba(0, 0, 0, 0.1); border-radius: 10px; }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb { background-color: rgba(249, 115, 22, 0.3); } /* hover orange */
</style>

<!-- Modal Tambah Jadwal Manual -->
<div id="addScheduleModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-xl font-bold text-gray-800">Tambah Jadwal Manual</h3>
            <button onclick="document.getElementById('addScheduleModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="p-6">
            <form action="{{ route('admin.schedule.manualAdd') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Siswa</label>
                        <select name="user_id" required class="w-full border border-gray-200 rounded-xl shadow-sm focus:bg-white focus:border-orange-500 focus:ring-orange-500 p-3 bg-gray-50 transition-colors">
                            <option value="">-- Pilih Siswa --</option>
                            @foreach(\App\Models\User::where('role', 'intern')->orderBy('name')->get() as $intern)
                                <option value="{{ $intern->id }}">{{ $intern->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="date" required class="w-full border border-gray-200 rounded-xl shadow-sm focus:bg-white focus:border-orange-500 focus:ring-orange-500 p-3 bg-gray-50 transition-colors">
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('addScheduleModal').classList.add('hidden')" class="px-5 py-2.5 text-gray-600 font-medium hover:bg-gray-100 rounded-xl transition-colors">Batal</button>
                    <button type="submit" class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-medium py-2.5 px-6 rounded-xl shadow-md transition-all transform hover:-translate-y-0.5">
                        Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tukar Jadwal -->
<div id="swapScheduleModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 overflow-hidden transform transition-all">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-xl font-bold text-gray-800">Tukar Jadwal Magang</h3>
            <button onclick="document.getElementById('swapScheduleModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="p-6">
            <form action="{{ route('admin.swap') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Jadwal Pertama</label>
                        <select name="schedule_id_1" required class="w-full border border-gray-200 rounded-xl shadow-sm focus:bg-white focus:border-orange-500 focus:ring-orange-500 p-3 bg-gray-50 transition-colors">
                            <option value="">-- Pilih Jadwal --</option>
                            @foreach($schedules as $s)
                                <option value="{{ $s->id }}">{{ \Carbon\Carbon::parse($s->date)->translatedFormat('d M Y') }} - {{ $s->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex justify-center my-2">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Jadwal Kedua</label>
                        <select name="schedule_id_2" required class="w-full border border-gray-200 rounded-xl shadow-sm focus:bg-white focus:border-orange-500 focus:ring-orange-500 p-3 bg-gray-50 transition-colors">
                            <option value="">-- Pilih Jadwal --</option>
                            @foreach($schedules as $s)
                                <option value="{{ $s->id }}">{{ \Carbon\Carbon::parse($s->date)->translatedFormat('d M Y') }} - {{ $s->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('swapScheduleModal').classList.add('hidden')" class="px-5 py-2.5 text-gray-600 font-medium hover:bg-gray-100 rounded-xl transition-colors">Batal</button>
                    <button type="submit" class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-medium py-2.5 px-6 rounded-xl shadow-md transition-all transform hover:-translate-y-0.5">
                        Tukar Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
