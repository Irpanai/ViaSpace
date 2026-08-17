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

        <!-- Kelola Hari Libur Button -->
        <button onclick="document.getElementById('holidayModal').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-red-50 text-red-600 font-semibold text-sm border border-red-100 hover:bg-red-100 transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Kelola Hari Libur
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
                $holiday = $holidays->get($currentDateStr);
            @endphp
            
            <div class="min-h-[100px] md:min-h-[140px] p-2 md:p-3 rounded-2xl border {{ $holiday ? 'border-red-200 bg-red-50' : ($isToday ? 'border-orange-300 bg-gradient-to-b from-orange-50/50 to-white shadow-sm ring-1 ring-orange-100' : 'border-gray-100 hover:border-gray-300 hover:shadow-md transition-all duration-300 bg-white') }} flex flex-col group relative">
                <!-- Tanggal Header -->
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-bold w-8 h-8 flex items-center justify-center rounded-xl {{ $holiday ? 'text-red-600 bg-red-100' : ($isToday ? 'text-white bg-gradient-to-tr from-orange-500 to-orange-400 shadow-md shadow-orange-500/20' : 'text-gray-600 group-hover:bg-gray-100') }}">
                        {{ $day }}
                    </span>
                    @if(!$holiday && $daySchedules->count() > 0)
                        <span class="text-[10px] font-bold bg-gray-100 text-gray-500 px-2 py-1 rounded-lg group-hover:bg-orange-100 group-hover:text-orange-600 transition-colors">
                            {{ $daySchedules->count() }}
                        </span>
                    @endif
                </div>

                @if($holiday)
                    <div class="flex-1 flex flex-col items-center justify-center text-center opacity-80">
                        <svg class="w-6 h-6 text-red-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        <span class="text-xs font-bold text-red-600 uppercase tracking-wide">LIBUR</span>
                        <span class="text-[10px] font-medium text-red-500 leading-tight mt-0.5">{{ $holiday->name }}</span>
                    </div>
                @else
                    <!-- Daftar Siswa Masuk -->
                    <div class="flex-1 overflow-y-auto custom-scrollbar flex flex-wrap gap-1.5 items-start content-start">
                        @foreach($daySchedules as $schedule)
                            @php
                                $attKey = $currentDateStr . '_' . $schedule->user_id;
                                $att = $attendances->get($attKey);
                                $bgColor = 'bg-gray-50';
                                $borderColor = 'border-gray-100';
                                $textColor = 'text-gray-600';
                                $hoverColor = 'hover:bg-gray-100';
                                $titleStatus = 'Belum Absen';

                                if ($att) {
                                    if ($att->status == 'present') {
                                        $bgColor = 'bg-green-50'; $borderColor = 'border-green-200'; $textColor = 'text-green-700'; $hoverColor = 'hover:bg-green-100'; $titleStatus = 'Hadir';
                                    } elseif ($att->status == 'late') {
                                        $bgColor = 'bg-orange-50'; $borderColor = 'border-orange-200'; $textColor = 'text-orange-700'; $hoverColor = 'hover:bg-orange-100'; $titleStatus = 'Terlambat';
                                    } elseif ($att->status == 'sakit' || $att->status == 'sick') {
                                        $bgColor = 'bg-red-50'; $borderColor = 'border-red-200'; $textColor = 'text-red-700'; $hoverColor = 'hover:bg-red-100'; $titleStatus = 'Sakit';
                                    } elseif ($att->status == 'izin' || $att->status == 'permit') {
                                        $bgColor = 'bg-blue-50'; $borderColor = 'border-blue-200'; $textColor = 'text-blue-700'; $hoverColor = 'hover:bg-blue-100'; $titleStatus = 'Izin';
                                    } elseif ($att->status == 'alpha') {
                                        $bgColor = 'bg-gray-200'; $borderColor = 'border-gray-300'; $textColor = 'text-gray-800'; $hoverColor = 'hover:bg-gray-300'; $titleStatus = 'Alpha';
                                    }
                                } elseif (\Carbon\Carbon::parse($currentDateStr)->isBefore(\Carbon\Carbon::today())) {
                                    $bgColor = 'bg-gray-200'; $borderColor = 'border-gray-300'; $textColor = 'text-gray-800'; $hoverColor = 'hover:bg-gray-300'; $titleStatus = 'Alpha (Tidak Hadir)';
                                }
                            @endphp
                            <div class="flex items-center gap-1.5 px-2 py-1 rounded-full {{ $bgColor }} border {{ $borderColor }} {{ $hoverColor }} transition-colors" title="{{ $schedule->user->name }} ({{ $titleStatus }})">
                                <!-- Avatar Mini -->
                                <div class="w-4 h-4 md:w-5 md:h-5 rounded-full flex-shrink-0 bg-white flex items-center justify-center text-[9px] font-bold text-gray-600 overflow-hidden shadow-sm border border-gray-100">
                                    @if($schedule->user->avatar)
                                        <img src="{{ Storage::url($schedule->user->avatar) }}" alt="{{ $schedule->user->name }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($schedule->user->name, 0, 1) }}
                                    @endif
                                </div>
                                <!-- Nama -->
                                <span class="text-[9px] md:text-[10px] font-semibold truncate {{ $textColor }}">
                                    {{ $schedule->user->nickname ? $schedule->user->nickname : explode(' ', trim($schedule->user->name))[0] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
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

<!-- Modal Kelola Hari Libur -->
<div id="holidayModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 overflow-hidden transform transition-all flex flex-col max-h-[90vh]">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-xl font-bold text-gray-800">Kelola Hari Libur</h3>
            <button onclick="document.getElementById('holidayModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="p-6 overflow-y-auto flex-1">
            <!-- Form Tambah -->
            <form action="{{ route('admin.holidays.store') }}" method="POST" class="mb-8 p-4 bg-red-50 rounded-xl border border-red-100">
                @csrf
                <h4 class="font-bold text-red-800 mb-3 text-sm">Tambah Hari Libur Baru</h4>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-red-700 mb-1">Tanggal</label>
                        <input type="date" name="date" required class="w-full border border-red-200 rounded-lg shadow-sm focus:bg-white focus:border-red-500 focus:ring-red-500 p-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-red-700 mb-1">Keterangan Libur</label>
                        <input type="text" name="name" required placeholder="Contoh: Hari Kemerdekaan RI" class="w-full border border-red-200 rounded-lg shadow-sm focus:bg-white focus:border-red-500 focus:ring-red-500 p-2 text-sm">
                    </div>
                    <div class="text-right mt-2">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-colors text-sm">
                            Simpan Hari Libur
                        </button>
                    </div>
                </div>
            </form>

            <!-- Daftar Libur Bulan Ini -->
            <h4 class="font-bold text-gray-800 mb-3 text-sm">Daftar Libur di Bulan Ini</h4>
            @if($holidaysList->count() > 0)
                <div class="space-y-2">
                    @foreach($holidaysList as $holiday)
                        <div class="flex items-center justify-between p-3 rounded-lg border border-gray-100 bg-gray-50 hover:bg-white transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex flex-col items-center justify-center flex-shrink-0">
                                    <span class="text-xs font-bold leading-none">{{ \Carbon\Carbon::parse($holiday->date)->format('d') }}</span>
                                    <span class="text-[9px] font-medium uppercase leading-none">{{ \Carbon\Carbon::parse($holiday->date)->translatedFormat('M') }}</span>
                                </div>
                                <div>
                                    <div class="font-bold text-sm text-gray-800">{{ $holiday->name }}</div>
                                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($holiday->date)->translatedFormat('l') }}</div>
                                </div>
                            </div>
                            <form action="{{ route('admin.holidays.destroy', $holiday->id) }}" method="POST" onsubmit="return confirm('Hapus hari libur ini? Jadwal yang tersembunyi akan muncul kembali.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-500 hover:bg-red-100 rounded-lg transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 bg-gray-50 rounded-xl border border-gray-100 border-dashed">
                    <p class="text-sm text-gray-500">Belum ada hari libur di bulan ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>

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
